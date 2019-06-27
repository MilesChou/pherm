<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Binding\Key;
use MilesChou\Pherm\Concerns\BufferTrait;
use MilesChou\Pherm\Concerns\ConfigTrait;
use MilesChou\Pherm\Concerns\InstantOutputTrait;
use MilesChou\Pherm\Concerns\IoTrait;
use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;

class Terminal implements TerminalContract
{
    use BufferTrait;
    use ConfigTrait;
    use InstantOutputTrait;
    use IoTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @var int
     */
    private $currentBackground;

    /**
     * @var int
     */
    private $currentForeground;

    /**
     * @var CursorContract
     */
    private $cursor;

    /**
     * @var Key
     */
    private $keyBinding;

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
        }

        if (null === $control) {
            $this->control = new Control();
        }
    }

    public function attribute(?int $foreground = null, ?int $background = null)
    {
        $this->currentForeground = $foreground;
        $this->currentBackground = $background;

        return $this;
    }

    private function background(?int $background)
    {
        if (null === $background && null === $this->defaultBackground) {
            $this->output->write("\033[0m");
            return $this;
        }

        if (null === $background) {
            $background = $this->defaultBackground;
        }

        $background &= 0x1FF;

        if ($background !== $this->currentBackground) {
            $this->currentBackground = $background;
            $this->output->write("\033[48;5;{$background}m");
        }

        return $this;
    }

    private function foreground(?int $foreground)
    {
        if (null === $foreground && null === $this->defaultForeground) {
            $this->output->write("\033[0m");
            return $this;
        }

        if (null === $foreground) {
            $foreground = $this->defaultForeground;
        }

        $foreground &= 0x1FF;

        if ($foreground !== $this->currentForeground) {
            $this->currentForeground = $foreground;
            $this->output->write("\033[38;5;{$foreground}m");
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
     * @return static
     */
    public function clear()
    {
        // Clear terminal
        $this->output->write("\033[2J");

        // Clear buffer
        $this->backBuffer->clear($this->defaultForeground, $this->defaultBackground);
        $this->frontBuffer->clear($this->defaultForeground, $this->defaultBackground);

        // Initial the position to origin point 1, 1
        $this->cursor()->position(1, 1);

        return $this;
    }

    /**
     * @return Cursor
     */
    public function cursor(): Cursor
    {
        if (null === $this->cursor) {
            $this->cursor = new Cursor($this, $this->control);
        }

        return $this->cursor;
    }

    public function flush(): void
    {
        if ($this->isInstantOutput()) {
            return;
        }

        [$sizeX, $siezY] = $this->size();

        foreach ($this->getBuffer() as $index => $cell) {
            $y = (int)($index / $sizeX);
            $x = $index % $sizeX;

            $this->writeCursorInstant(
                $x,
                $y,
                $cell[0] ?? ' ',
                $cell[1] ?? $this->currentForeground,
                $cell[2] ?? $this->currentBackground
            );
        }
    }

    /**
     * @return OutputStream
     */
    public function getOutput(): OutputStream
    {
        return $this->output;
    }

    /**
     * Alias for cursor()
     *
     * @return Cursor|TerminalContract
     */
    public function move()
    {
        if (2 === func_num_args()) {
            return $this->cursor()->move(...func_get_args());
        }

        return $this->cursor();
    }

    public function write(string $char, ?int $fg = null, ?int $bg = null)
    {
        if ($this->isInstantOutput()) {
            $this->foreground($fg);
            $this->background($bg);
            $this->output->write($char);
        } else {
            [$x, $y] = $this->cursor->last();
            $this->writeCell($x, $y, $char, $fg, $bg);
        }

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $char
     * @return static
     */
    public function writeCursor(int $x, int $y, string $char)
    {
        if ($this->isInstantOutput()) {
            $this->writeCursorInstant($x, $y, $char, $this->currentForeground, $this->currentBackground);
        } else {
            $this->writeCell($x, $y, $char, $this->currentForeground, $this->currentBackground);
        }

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $char
     * @param int $fg
     * @param int $bg
     * @return static
     */
    private function writeCursorInstant(int $x, int $y, string $char, ?int $fg = null, ?int $bg = null)
    {
        $this->cursor()->move($x, $y);

        $this->foreground($fg);
        $this->background($bg);
        $this->output->write($char);

        return $this;
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
     * @return array [x, y]
     */
    public function size(): array
    {
        return [$this->width, $this->height];
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
