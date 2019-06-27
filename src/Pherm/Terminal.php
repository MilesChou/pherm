<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\CellsTrait;
use MilesChou\Pherm\Concerns\ConfigTrait;
use MilesChou\Pherm\Concerns\IoTrait;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;
use MilesChou\Pherm\Concerns\InstantOutputTrait;

class Terminal implements TerminalContract
{
    use CellsTrait;
    use ConfigTrait;
    use InstantOutputTrait;
    use IoTrait;

    /**
     * @var int
     */
    private $currentBackground;

    /**
     * @var int
     */
    private $currentForeground;

    /**
     * @var KeyBinding
     */
    private $keyBinding;

    /**
     * @var Control
     */
    private $control;

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
        if ($foreground === null) {
            $foreground = $this->defaultForeground;
        }

        if ($background === null) {
            $background = $this->defaultBackground;
        }

        $this->background($background);
        $this->foreground($foreground);

        return $this;
    }

    public function background(int $background)
    {
        $background &= 0x1FF;

        if ($background !== $this->currentBackground) {
            $this->currentBackground = $background;
            $this->write("\033[48;5;{$background}m");
        }

        return $this;
    }

    /**
     * @return static
     */
    public function bootstrap()
    {
        $this->prepareConfiguration();

        return $this;
    }

    public function foreground(int $foreground)
    {
        $foreground &= 0x1FF;

        if ($foreground !== $this->currentForeground) {
            $this->currentForeground = $foreground;
            $this->write("\033[38;5;{$foreground}m");
        }

        return $this;
    }

    /**
     * @return Cursor
     */
    public function cursor(): Cursor
    {
        return new Cursor($this, $this->control);
    }

    public function flushCells(): void
    {
        [$sizeX, $siezY] = $this->size();

        foreach ($this->getCells() as $index => $cell) {
            $y = (int)($index / $sizeX);
            $x = $index % $sizeX;

            $this->writeCursor($x, $y, $cell[0] ?? ' ');
        }
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

    /**
     * @param int $column
     * @param int $row
     * @param string $buffer
     * @return static
     */
    public function writeCursor(int $column, int $row, string $buffer)
    {
        $this->cursor()->move($column, $row);
        $this->output->write($buffer);

        return $this;
    }

    /**
     * @return KeyBinding
     */
    public function keyBinding()
    {
        if (null === $this->keyBinding) {
            $this->keyBinding = new KeyBinding($this);
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
    }
}
