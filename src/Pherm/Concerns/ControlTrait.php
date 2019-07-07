<?php

namespace MilesChou\Pherm\Concerns;

use LogicException;
use MilesChou\Pherm\Contracts\Control;
use OutOfRangeException;

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
 *
 * @see https://www.xfree86.org/current/ctlseqs.html
 */
trait ControlTrait
{
    /**
     * @var array
     */
    private $overwrite;

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            throw new LogicException("Key '$key' has the same name method, use method instead");
        }

        return $this->characters($key);
    }

    public function __isset($key)
    {
        return isset(Control::CONTROL_CHARACTER[$key]) || isset(Control::CONTROL_SEQUENCES[$key]);
    }

    public function __set($name, $value)
    {
        throw new LogicException('Cannot set the value on Control');
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
    public function overwrite(array $overwrite)
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
            return Control::CONTROL_CHARACTER[$key] ?? Control::CONTROL_SEQUENCES[$key];
        }

        throw new OutOfRangeException("Key '$key' is not defined");
    }
}
