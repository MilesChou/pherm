<?php

namespace MilesChou\Pherm;

use InvalidArgumentException;
use MilesChou\Pherm\Concerns\AttributeTrait;
use MilesChou\Pherm\Concerns\BufferTrait;
use MilesChou\Pherm\Concerns\IoTrait;
use MilesChou\Pherm\Concerns\PositionAwareTrait;
use MilesChou\Pherm\Concerns\SizeAwareTrait;
use MilesChou\Pherm\Contracts\Input;
use MilesChou\Pherm\Contracts\Output;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\Attributes\Color256;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Support\Char;
use Psr\Container\ContainerInterface;

class Terminal
{
    use SizeAwareTrait;
    use AttributeTrait;
    use BufferTrait;
    use IoTrait;
    use PositionAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @var bool
     */
    private $isInstantOutput = false;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $input = $container->has(Input::class) ? $container->get(Input::class) : new InputStream();
        $output = $container->has(Output::class) ? $container->get(Output::class) : new OutputStream();

        $this->setInput($input);
        $this->setOutput($output);

        $tty = $container->has(TTY::class) ? $container->get(TTY::class) : new TTY();

        $this->control = new Control($tty);

        // TODO: Now just use Color256
        $this->attribute = new Color256();
        $this->renderer = new Renderer($this->output, $this->control);
    }

    /**
     * Proxy to Attribute object
     *
     * @param int|null $fg
     * @param int|null $bg
     * @return Terminal
     */
    public function attribute(?int $fg = null, ?int $bg = null): Terminal
    {
        if ($this->isInstantOutput()) {
            if ($fg === $this->lastFg && $bg === $this->lastBg) {
                return $this;
            }

            $this->output->write($this->attribute->generate($fg, $bg));
        }

        $this->lastFg = $fg;
        $this->lastBg = $bg;

        return $this;
    }

    /**
     * @return Terminal
     */
    public function bootstrap(): Terminal
    {
        $tty = $this->control->tty();

        $this->height = $tty->height();
        $this->width = $tty->width();

        $this->prepareCellBuffer();

        $tty->store();

        return $this;
    }

    /**
     * Clear screen and buffer
     *
     * @param int|null $fg
     * @param int|null $bg
     * @return Terminal
     */
    public function clear(?int $fg = null, ?int $bg = null): Terminal
    {
        // Clear terminal
        $this->output->write("\033[2J");

        // Clear backend buffer
        $this->getCellBuffer()->clear($fg, $bg);

        // Reset cursor
        $this->setPosition(1, 1);

        return $this;
    }

    /**
     * @return Control
     */
    public function control(): Control
    {
        return $this->control;
    }

    /**
     * Current cursor position
     *
     * @return array
     */
    public function current(): array
    {
        $tty = $this->control->tty();

        // store state
        $icanon = $tty->isCanonicalMode();
        $echo = $tty->isEchoBack();

        if ($icanon) {
            $tty->disableCanonicalMode();
        }

        if ($echo) {
            $tty->disableEchoBack();
        }

        fwrite(STDOUT, $this->control->dsr);

        // 16 is work when return "\033[xxx;xxxH"
        if (!$cpr = fread(STDIN, 16)) {
            return [-1, -1];
        }

        // restore state
        if ($icanon) {
            $tty->enableCanonicalMode();
        }

        if ($echo) {
            $tty->enableEchoBack();
        }

        if (sscanf(trim($cpr), $this->control->cpr, $row, $col) === 2) {
            return [$col, $row];
        }

        return [-1, -1];
    }

    /**
     * @return Cursor
     */
    public function cursor(): Cursor
    {
        return new Cursor($this, $this->control);
    }

    /**
     * @return Terminal
     */
    public function disableInstantOutput(): Terminal
    {
        $this->isInstantOutput = false;

        return $this;
    }

    /**
     * @return Terminal
     */
    public function enableInstantOutput(): Terminal
    {
        $this->isInstantOutput = true;

        return $this;
    }

    /**
     * Flush buffer to output
     */
    public function flush(): void
    {
        $this->renderer->renderBuffer($this->getCellBuffer());

        $this->output->flush();
    }

    /**
     * @return bool
     */
    public function isInstantOutput(): bool
    {
        return $this->isInstantOutput;
    }

    /**
     * @param int $x
     * @param int $y
     * @return Terminal
     */
    public function moveCursor(int $x, int $y): Terminal
    {
        if ($this->isInstantOutput()) {
            $this->output->write($this->control->move($x, $y));
        } else {
            $this->control->checkPosition($x, $y);

            $this->setPosition($x, $y);
        }

        return $this;
    }

    /**
     * @param int $bytes
     * @return string
     */
    public function read(int $bytes): string
    {
        $buffer = '';

        $this->input->read($bytes, function ($data) use (&$buffer) {
            $buffer .= $data;
        });

        return $buffer;
    }

    /**
     * @param string $buffer
     */
    public function write(string $buffer): void
    {
        foreach (Char::charsToArray($buffer) as $char) {
            $this->writeChar($char);
        }
    }

    /**
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->control->tty()->restore();
        $this->enableCursor();
    }

    /**
     * @param string $char
     * @return static
     */
    private function writeChar(string $char): Terminal
    {
        if (mb_strlen($char) > 1) {
            throw new InvalidArgumentException('Char must be only one mbstring');
        }

        if ($this->isInstantOutput()) {
            $this->output->write($char);
        } else {
            $this->writeBuffer($char);
        }

        return $this;
    }

    private function writeBuffer(string $char): void
    {
        [$x, $y] = $this->getPosition();

        if ($char === "\n") {
            if ($y + 1 > $this->height) {
                return;
            }

            $this->setPosition(1, $y + 1);

            return;
        }

        $this->getCellBuffer()->set($x, $y, $char, $this->lastFg, $this->lastBg);

        if ($x + 1 > $this->width) {
            if ($y + 1 > $this->height) {
                return;
            }

            $x = 0;
            $y++;
        }

        $this->setPosition($x + 1, $y);
    }
}
