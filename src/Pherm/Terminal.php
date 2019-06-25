<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\Configuration;
use MilesChou\Pherm\Concerns\Io;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;

class Terminal implements TerminalContract
{
    use Configuration;
    use Io;

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
     * @param InputStream|null $input
     * @param OutputStream|null $output
     */
    public function __construct(InputStream $input = null, OutputStream $output = null)
    {
        if (null !== $input) {
            $this->setInput($input);
        }

        if (null !== $output) {
            $this->setOutput($output);
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
        return new Cursor($this);
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
     * @param int $row
     * @param int $column
     * @param string $buffer
     * @return static
     */
    public function writeCursor(int $row, int $column, string $buffer)
    {
        $this->cursor()->move($row, $column);
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
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->stty->restore();
    }
}
