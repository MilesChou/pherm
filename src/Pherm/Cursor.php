<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\ConfigTrait;
use MilesChou\Pherm\Concerns\ControlTrait;
use MilesChou\Pherm\Contracts\OutputStream;
use OverflowException;

/**
 * CSI generator for move Cursor
 */
class Cursor
{
    use ConfigTrait;
    use ControlTrait;

    /**
     * @var OutputStream
     */
    private $output;

    /**
     * @param TTY $tty
     */
    public function __construct(TTY $tty)
    {
        $this->tty = $tty;

        $this->prepareConfiguration();
    }

    public function move(int $x, int $y): string
    {
        $this->checkPosition($x, $y);

        return $this->cup($y, $x);
    }

    /**
     * @param int $x
     * @param int $y
     */
    public function checkPosition(int $x, int $y): void
    {
        if ($x < 1 || $x > $this->width) {
            throw new OverflowException("X expect in range 1 to {$this->width}, actual is '$x'");
        }

        if ($y < 1 || $y > $this->height) {
            throw new OverflowException("Y expect in range 1 to {$this->height}, actual is '$y'");
        }
    }
}
