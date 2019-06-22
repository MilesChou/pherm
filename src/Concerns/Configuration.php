<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Stty;

trait Configuration
{
    /**
     * @var int
     */
    private $colourSupport;

    /**
     * @var bool
     */
    private $echoBack = true;

    /**
     * @var int
     */
    private $height;

    /**
     * @var bool
     */
    private $isCanonical = true;

    /**
     * @var Stty
     */
    private $stty;

    /**
     * @var int
     */
    private $width;

    public function disableCanonicalMode(): void
    {
        $this->stty->exec('-icanon');
        $this->isCanonical = false;
    }

    public function disableEchoBack(): void
    {
        $this->stty->exec('-echo');
        $this->echoBack = false;
    }

    public function enableCanonicalMode(): void
    {
        $this->stty->exec('icanon');
        $this->isCanonical = true;
    }

    public function enableEchoBack(): void
    {
        $this->stty->exec('echo');
        $this->echoBack = true;
    }

    public function getColourSupport(): int
    {
        return $this->colourSupport;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    public function isCanonicalMode(): bool
    {
        return $this->isCanonical;
    }

    public function isEchoBack(): bool
    {
        return $this->echoBack;
    }

    /**
     * @param Stty $stty
     * @return static
     */
    public function setStty(Stty $stty)
    {
        $this->stty = $stty;

        return $this;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    protected function prepareConfiguration(): void
    {
        if (null === $this->stty) {
            $this->setStty(new Stty());
        }

        $this->stty->store();
        $parsed = $this->stty->parseAll();

        $this->echoBack = $parsed['echo'];
        $this->isCanonical = $parsed['icanon'];
        $this->height = $parsed['rows'];
        $this->width = $parsed['columns'];

        $this->colourSupport = (int)exec('tput colors');
    }
}
