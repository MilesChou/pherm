<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Stty;

trait ConfigTrait
{
    use SizeAwareTrait;

    /**
     * @var bool
     */
    private $echoBack;

    /**
     * @var bool
     */
    private $isCanonical;

    /**
     * @var Stty
     */
    private $stty;

    public function disableCanonicalMode()
    {
        $this->stty->exec('-icanon');
        $this->isCanonical = false;

        return $this;
    }

    public function disableEchoBack()
    {
        $this->stty->exec('-echo');
        $this->echoBack = false;

        return $this;
    }

    public function enableCanonicalMode()
    {
        $this->stty->exec('icanon');
        $this->isCanonical = true;

        return $this;
    }

    public function enableEchoBack()
    {
        $this->stty->exec('echo');
        $this->echoBack = true;

        return $this;
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
    }
}
