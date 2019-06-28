<?php

namespace MilesChou\Pherm;

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
            // TODO: Now just use Color256
            $this->attribute = new Color256($output);
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

        foreach ($this->getBuffer() as $index => $cell) {
            $y = (int)($index / $sizeX);
            $x = $index % $sizeX;

            $this->writeCursor($x, $y, $cell[0] ?? ' ');
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
