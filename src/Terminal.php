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
     * @param InputStream $input
     * @param OutputStream $output
     */
    public function __construct(InputStream $input, OutputStream $output)
    {
        $this->setInput($input);
        $this->setOutput($output);
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
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->stty->restore();
    }
}
