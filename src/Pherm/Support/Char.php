<?php

namespace MilesChou\Pherm\Support;

use InvalidArgumentException;

/**
 * @see https://github.com/mattn/go-runewidth
 */
class Char
{
    /**
     * @param string $char
     */
    public static function checkLength(string $char): void
    {
        if (mb_strlen($char) !== 1) {
            throw new InvalidArgumentException("Char length must be '1'");
        }
    }

    /**
     * Transform the mbstring to an array
     *
     * @param string $str
     * @return array<string>
     */
    public static function charsToArray(string $str): array
    {
        if ('' === $str) {
            return [];
        }

        $arr = [];
        $len = mb_strlen($str);

        for ($i = 0; $i < $len; $i++) {
            $arr[] = mb_substr($str, $i, 1);
        }

        return array_reduce($arr, function ($carry, $char) {
            $carry[] = $char;

            if (static::width($char) === 2) {
                $carry[] = "\0";
            }

            return $carry;
        }, []);
    }

    /**
     * @param string $char
     * @return int
     */
    public static function width(string $char): int
    {
        static::checkLength($char);

        $c = mb_ord($char);

        if ($c < 0 || $c > 0x10FFFF) {
            return 0;
        }

        if (self::inTable($c, WidthTables::DOUBLE_WIDTH)) {
            return 2;
        }

        return 1;
    }

    /**
     * @param int $c
     * @param array<array<mixed>> $table
     * @return bool
     */
    private static function inTable(int $c, array $table): bool
    {
        if ($c < $table[0][0]) {
            return false;
        }

        // Binary search
        $bot = 0;
        $top = count($table) - 1;

        while ($top >= $bot) {
            $mid = ($bot + $top) >> 1;

            if ($table[$mid][1] < $c) {
                $bot = $mid + 1;
            } elseif ($table[$mid][0] > $c) {
                $top = $mid - 1;
            } else {
                return true;
            }
        }

        return false;
    }
}
