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

    /**
     * @return static
     */
    public function bootstrap()
    {
        $this->prepareConfiguration();

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
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->stty->restore();
    }
}
