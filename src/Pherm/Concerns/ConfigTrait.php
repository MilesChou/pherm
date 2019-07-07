<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\TTY;

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
     * @var TTY
     */
    private $tty;

    public function disableCanonicalMode()
    {
        $this->tty->exec('-icanon');
        $this->isCanonical = false;

        return $this;
    }

    public function disableEchoBack()
    {
        $this->tty->exec('-echo');
        $this->echoBack = false;

        return $this;
    }

    public function enableCanonicalMode()
    {
        $this->tty->exec('icanon');
        $this->isCanonical = true;

        return $this;
    }

    public function enableEchoBack()
    {
        $this->tty->exec('echo');
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
     * @param TTY $tty
     * @return static
     */
    public function setTTY(TTY $tty)
    {
        $this->tty = $tty;

        return $this;
    }

    protected function prepareConfiguration(): void
    {
        if (null === $this->tty) {
            $this->setTTY(new TTY());
        }

        $this->tty->store();
        $parsed = $this->tty->parseAll();

        $this->echoBack = $parsed['echo'];
        $this->isCanonical = $parsed['icanon'];

        $this->height = $this->tty->height();
        $this->width = $this->tty->width();
    }
}
