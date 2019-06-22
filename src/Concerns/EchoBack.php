<?php

namespace MilesChou\Pherm\Concerns;

/**
 * Whether terminal echo back is enabled or not.
 * Eg. user key presses and the terminal immediately shows it.
 */
trait EchoBack
{
    /**
     * @var bool
     */
    private $echoBack = true;

    public function disableEchoBack(): void
    {
        exec('stty -echo');
        $this->echoBack = false;
    }

    public function enableEchoBack(): void
    {
        exec('stty echo');
        $this->echoBack = true;
    }

    public function isEchoBack(): bool
    {
        return $this->echoBack;
    }
}
