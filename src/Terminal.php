<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\Configuration;
use MilesChou\Pherm\Concerns\EchoBack;
use MilesChou\Pherm\Concerns\Io;
use MilesChou\Pherm\Concerns\Size;
use MilesChou\Pherm\Contracts\Terminal as TerminalContract;
use MilesChou\Pherm\IO\InputStream;
use MilesChou\Pherm\IO\OutputStream;

class Terminal implements TerminalContract
{
    use Configuration;
    use EchoBack;
    use Io;
    use Size;

    /**
     * @var bool
     */
    private $isCanonical;

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
        $this->isCanonical = (strpos(implode("\n", $output), ' icanon') !== false);
    }

    public function getColourSupport(): int
    {
        return $this->colourSupport ?: $this->colourSupport = (int)exec('tput colors');
    }

    public function disableCanonicalMode(): void
    {
        if ($this->isCanonical) {
            exec('stty -icanon');
            $this->isCanonical = false;
        }
    }

    public function enableCanonicalMode(): void
    {
        if (!$this->isCanonical) {
            exec('stty canon');
            $this->isCanonical = true;
        }
    }

    public function isCanonicalMode(): bool
    {
        return $this->isCanonical;
    }

    /**
     * Restore the original terminal configuration on shutdown.
     */
    public function __destruct()
    {
        $this->restoreOriginalConfiguration();
    }
}
