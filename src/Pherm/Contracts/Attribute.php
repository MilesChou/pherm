<?php

namespace MilesChou\Pherm\Contracts;

/**
 * Interface for send attribute
 */
interface Attribute
{
    /**
     * Define invalid attr
     */
    public const INVALID = 0xFFFF;

    /**
     * Define colors
     */
    public const COLOR_DEFAULT = null;
    public const COLOR_BLACK = 0;
    public const COLOR_RED = 1;
    public const COLOR_GREEN = 2;
    public const COLOR_YELLOW = 3;
    public const COLOR_BLUE = 4;
    public const COLOR_MAGENTA = 5;
    public const COLOR_CYAN = 6;
    public const COLOR_WHITE = 7;
    public const COLOR_BRIGHT_BLACK = 8;
    public const COLOR_BRIGHT_RED = 9;
    public const COLOR_BRIGHT_GREEN = 10;
    public const COLOR_BRIGHT_YELLOW = 11;
    public const COLOR_BRIGHT_BLUE = 12;
    public const COLOR_BRIGHT_MAGENTA = 13;
    public const COLOR_BRIGHT_CYAN = 14;
    public const COLOR_BRIGHT_WHITE = 15;

    /**
     * Define attributes
     */
    public const BOLD = 512;
    public const UNDER_LINE = 1024;
    public const REVERSE = 2048;

    /**
     * @param int|null $fg
     * @param int|null $bg
     * @return string
     */
    public function generate(?int $fg, ?int $bg): string;
}
