<?php

namespace MilesChou\Pherm;

use LogicException;
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
 * @property-read string $cuu Cursor Up
 * @property-read string $cud Cursor Down
 * @property-read string $cuf Cursor Forward
 * @property-read string $cub Cursor Backward
 * @property-read string $ed Erase in Display
 * @property-read string $rcp
 * @property-read string $scp
 */
class Control
{
    /**
     * array key is mnemonic and value is character
     *
     * @see https://en.wikipedia.org/wiki/Control_character
     */
    public const CONTROL_CHARACTER = [
        'NUL' => "\x00",
        'ETX' => "\x03",
        'EOT' => "\x04",
        'BEL' => "\x07",
        'BS' => "\x08",
        'LF' => "\x0A",
        'CR' => "\x0D",
        'CAN' => "\x18",
        'ESC' => "\x1B",
    ];

    /**
     * array key is mnemonic and value is control sequences
     */
    public const CONTROL_SEQUENCES = [
        'CUU' => "\033[A",
        'CUD' => "\033[B",
        'CUF' => "\033[C",
        'CUB' => "\033[D",
        'CUP' => "\033[%d;%dH",
        'ED' => "\033[2J",
        'HVP' => "\033[%d;%df",
        'RCP' => "\033[u",
        'SCP' => "\033[S",
    ];

    /**
     * @var array
     */
    private $overwrite;

    /**
     * @param array $overwrite
     */
    public function __construct($overwrite = [])
    {
        $this->overwrite = $overwrite;
    }

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            throw new LogicException("Key '$key' has the same name method, use method instead");
        }

        return $this->characters($key);
    }

    public function __isset($key)
    {
        return isset(self::CONTROL_CHARACTER[$key]) || isset(self::CONTROL_SEQUENCES[$key]);
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
     * @param int $row
     * @param int $col
     * @return string
     */
    public function hvp(int $row, int $col): string
    {
        return sprintf($this->characters('HVP'), $row, $col);
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
