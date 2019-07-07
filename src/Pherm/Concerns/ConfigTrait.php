<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\TTY;

trait ConfigTrait
{
    use SizeAwareTrait;

    /**
     * @var TTY
     */
    private $tty;

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

        $this->height = $this->tty->height();
        $this->width = $this->tty->width();
    }
}
