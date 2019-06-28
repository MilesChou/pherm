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
     * @var Cursor
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
            // TODO: Now just use Color256
            $this->attribute = new Color256($output);
        }

        if (null === $control) {
            $control = new Control();
        }

        $this->control = $control;
        $this->cursor = new Cursor($this, $this->control);
    }

    public function attribute(?int $foreground = null, ?int $background = null)
    {
        $this->attribute->send($foreground, $background);

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

    public function flush(): void
    {
        foreach ($this->getBuffer() as $index => $cell) {
            $y = (int)($index / $this->width);
            $x = $index % $this->width;

            $this->cursor($x + 1, $y + 1);
            $this->attribute($cell[1], $cell[2]);
            $this->write($cell[0]);
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
     * @return Cursor|TerminalContract
     */
    public function moveCursor()
    {
        if (2 === func_num_args()) {
            return $this->cursor->move(...func_get_args());
        }

        return $this->cursor;
    }

    /**
     * @param int $column
     * @param int $row
     * @param string $buffer
     * @return static
     */
    public function writeCursor(int $column, int $row, string $buffer)
    {
        $this->cursor->move($column, $row);
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
