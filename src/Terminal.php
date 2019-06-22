<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\Canonical;
use MilesChou\Pherm\Concerns\Configuration;
use MilesChou\Pherm\Concerns\EchoBack;
use MilesChou\Pherm\Concerns\Io;
use MilesChou\Pherm\Concerns\Size;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;

class Terminal implements TerminalContract
{
    use Canonical;
    use Configuration;
    use EchoBack;
    use Io;
    use Size;

    /**
     * @var int;
     */
    private $colourSupport;

    /**
     * @param InputStream $input
     * @param OutputStream $output
     */
    public function __construct(InputStream $input, OutputStream $output)
    {
        $this->getOriginalConfiguration();
        $this->getOriginalCanonicalMode();
        $this->setInput($input);
        $this->setOutput($output);
    }

    private function getOriginalCanonicalMode(): void
    {
        exec('stty -a', $output);
        $this->isCanonical = strpos(implode("\n", $output), ' icanon') !== false;
    }

    public function getColourSupport(): int
    {
        return $this->colourSupport ?: $this->colourSupport = (int)exec('tput colors');
    }

    /**
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->restoreOriginalConfiguration();
    }
}
