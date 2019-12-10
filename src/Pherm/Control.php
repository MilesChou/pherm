<?php

namespace MilesChou\Pherm;

use LogicException;
use MilesChou\Pherm\Contracts\Control as ControlContract;
use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\TTY as TTYContract;
use OutOfRangeException;
use OverflowException;

/**
 * @property-read string $nul Null
 * @property-read string $etx End of Text
 * @property-read string $eot End of Transmission
 * @property-read string $bel Bell
 * @property-read string $bs Backspace
 * @property-read string $lf Line Feed
 * @property-read string $cr Carriage Return
 * @property-read string $can Cancel
 * @property-read string $esc Escape
 * @property-read string $cpr Cursor Position Report
 * @property-read string $cuu Cursor Up
 * @property-read string $cud Cursor Down
 * @property-read string $cuf Cursor Forward
 * @property-read string $cub Cursor Backward
 * @property-read string $dsr Device Status Report
 * @property-read string $ed Erase in Display
 * @property-read string $rcp
 * @property-read string $scp
 * @property-read TTY $tty
 *
 * @see https://www.xfree86.org/current/ctlseqs.html
 */
class Control implements ControlContract, CursorContract
{
    /**
     * @var array
     */
    private $overwrite;

    /**
     * @var TTYContract
     */
    private $tty;

    /**
     * @param TTYContract $tty
     */
    public function __construct(TTYContract $tty)
    {
        $this->tty = $tty;
    }

    public function __get($key)
    {
        if ('tty' === $key) {
            return $this->tty;
        }

        if (method_exists($this, $key)) {
            throw new LogicException("Key '$key' has the same name method, use method instead");
        }

        return $this->characters($key);
    }

    public function __isset($key)
    {
        return isset(static::CONTROL_CHARACTER[$key]) || isset(static::CONTROL_SEQUENCES[$key]);
    }

    public function __set($name, $value)
    {
        throw new LogicException('Cannot set the value on Control');
    }

    /**
     * @inheritDoc
     */
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
        $width = $this->tty->width();
        $height = $this->tty->height();

        if ($x < 1 || $x > $width) {
            throw new OverflowException("X expect in range 1 to {$width}, actual is '{$x}'");
        }

        if ($y < 1 || $y > $height) {
            throw new OverflowException("Y expect in range 1 to {$height}, actual is '{$y}'");
        }
    }

    /**
     * Cursor Position
     *
     * @param int $row
     * @param int $col
     * @return string
     */
    public function cup(int $row, int $col): string
    {
        return sprintf($this->characters('CUP'), $row, $col);
    }

    /**
     * Horz & Vertical Position
     *
     * Same as CUP
     *
     * @param int $row
     * @param int $col
     * @return string
     */
    public function hvp(int $row, int $col): string
    {
        return sprintf($this->characters('HVP'), $row, $col);
    }

    /**
     * @param array $overwrite
     * @return static
     */
    public function overwrite(array $overwrite): Control
    {
        $this->overwrite = $overwrite;

        return $this;
    }

    /**
     * @param string $key
     * @return string
     */
    private function characters(string $key): string
    {
        $key = strtoupper($key);

        if (isset($this->overwrite[$key])) {
            return $this->overwrite[$key];
        }

        if ($this->__isset($key)) {
            return self::CONTROL_CHARACTER[$key] ?? self::CONTROL_SEQUENCES[$key];
        }

        throw new OutOfRangeException("Key '$key' is not defined");
    }
}
