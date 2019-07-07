<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\ControlTrait;
use MilesChou\Pherm\Concerns\TTYTrait;
use MilesChou\Pherm\Contracts\Control as ControlContract;
use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\TTY as TTYContract;
use OverflowException;

/**
 * CSI
 */
class Control implements ControlContract, CursorContract, TTYContract
{
    use ControlTrait;
    use TTYTrait;

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
        $width = $this->width();
        $height = $this->height();

        if ($x < 1 || $x > $width) {
            throw new OverflowException("X expect in range 1 to {$width}, actual is '{$x}'");
        }

        if ($y < 1 || $y > $height) {
            throw new OverflowException("Y expect in range 1 to {$height}, actual is '{$y}'");
        }
    }
}
