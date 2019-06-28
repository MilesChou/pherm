<?php

namespace MilesChou\Pherm;

use InvalidArgumentException;
use MilesChou\Pherm\Binding\Key;
use MilesChou\Pherm\Concerns\BufferTrait;
use MilesChou\Pherm\Concerns\ConfigTrait;
use MilesChou\Pherm\Concerns\InstantOutputTrait;
use MilesChou\Pherm\Concerns\IoTrait;
use MilesChou\Pherm\Contracts\Attribute;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;
use MilesChou\Pherm\Output\Attributes\Color256;
use MilesChou\Pherm\Support\Char;

class Terminal implements TerminalContract
{
    use BufferTrait;
    use ConfigTrait;
    use InstantOutputTrait;
    use IoTrait;

    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var Key
     */
    private $keyBinding;

    /**
     * @var int|null
     */
    private $currentForeground;

    /**
     * @var int|null
     */
    private $currentBackground;

    /**
     * @param InputStream|null $input
     * @param OutputStream|null $output
     * @param Control|null $control
     */
    public function __construct(InputStream $input = null, OutputStream $output = null, Control $control = null)
    {
        if (null !== $input) {
            $this->setInput($input);
        }

        if (null !== $output) {
            $this->setOutput($output);
            // TODO: Now just use Color256
            $this->attribute = new Color256($output);
        }

        if (null === $control) {
            $control = new Control();
        }

        $this->setControl($control);
        $this->setCursor(new Cursor($this, $control));
    }

    /**
     * Proxy to Attribute object
     *
     * @param int|null $foreground
     * @param int|null $background
     * @return static
     */
    public function attribute(?int $foreground = null, ?int $background = null)
    {
        if ($this->isInstantOutput()) {
            $this->attribute->send($foreground, $background);
        } else {
            $this->currentForeground = $foreground;
            $this->currentBackground = $background;
        }

        return $this;
    }

    /**
     * @return static
     */
    public function bootstrap()
    {
        $this->prepareConfiguration();
        $this->prepareBuffer();

        return $this;
    }

    /**
     * Clear screen and buffer
     *
     * @param int|null $fg
     * @param int|null $bg
     * @return static
     */
    public function clear(?int $fg = null, ?int $bg = null)
    {
        // Clear terminal
        $this->output->write("\033[2J");

        // Clear backend buffer
        $this->backBuffer->clear($fg, $bg);

        // Reset cursor
        $this->cursor->position(1, 1);

        return $this;
    }

    /**
     * Alias for moveCursor()
     *
     * @param array<int, mixed> $args
     * @return Cursor|TerminalContract
     */
    public function cursor(...$args)
    {
        return $this->moveCursor(...$args);
    }

    /**
     * Flush buffer to output
     */
    public function flush(): void
    {
        foreach ($this->getBuffer() as $index => $cell) {
            $y = (int)($index / $this->width);
            $x = $index % $this->width;

            $this->cursor->moveInstant($x + 1, $y + 1);
            $this->attribute->send($cell[1], $cell[2]);
            $this->output->write($cell[0]);
        }
    }

    /**
     * @return InputStream
     */
    public function getInput(): InputStream
    {
        return $this->input;
    }

    /**
     * @return OutputStream
     */
    public function getOutput(): OutputStream
    {
        return $this->output;
    }

    /**
     * @return Key
     */
    public function keyBinding()
    {
        if (null === $this->keyBinding) {
            $this->keyBinding = new Key($this);
        }

        return $this->keyBinding;
    }

    /**
     * @return Cursor|TerminalContract
     */
    public function moveCursor()
    {
        if (2 === func_num_args()) {
            return $this->cursor->move(...func_get_args());
        }

        return $this->cursor;
    }

    public function read(int $bytes): string
    {
        $buffer = '';
        $this->input->read($bytes, function ($data) use (&$buffer) {
            $buffer .= $data;
        });
        return $buffer;
    }

    /**
     * @return array [x, y]
     */
    public function size(): array
    {
        return [$this->width, $this->height];
    }

    public function write(string $buffer)
    {
        if ($this->isInstantOutput()) {
            $this->output->write($buffer);
        } else {
            foreach (Char::charsToArray($buffer) as $char) {
                $this->writeChar($char);
            }
        }
    }

    /**
     * @param string $char
     * @return static
     */
    public function writeChar(string $char)
    {
        if (mb_strlen($char) > 1) {
            throw new InvalidArgumentException('Char must be only one mbstring');
        }

        if ($this->isInstantOutput()) {
            $this->output->write($char);
        } else {
            [$x, $y] = $this->cursor->last();

            if ($char === "\n") {
                if ($y + 1 > $this->height) {
                    return $this;
                }
                $this->cursor->position(1, $y + 1);

                return $this;
            }

            $this->backBuffer->set($x, $y, $char, $this->currentForeground, $this->currentBackground);

            if ($x + 1 > $this->width) {
                if ($y + 1 > $this->height) {
                    return $this;
                }
                $x = 0;
                $y++;
            }

            $this->cursor->position($x + 1, $y);
        }

        return $this;
    }

    public function writeCursor(int $x, int $y, string $buffer)
    {
        if ($this->isInstantOutput()) {
            $this->cursor->move($x, $y);
            $this->output->write($buffer);
        } else {
            $this->backBuffer->set($x, $y, $buffer, $this->currentForeground, $this->currentBackground);
        }

        return $this;
    }

    /**
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->stty->restore();
        $this->enableCursor();
    }
}
