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
    public const COLOR_DEFAULT = 0;
    public const COLOR_BLACK = 1;
    public const COLOR_RED = 2;
    public const COLOR_GREEN = 3;
    public const COLOR_YELLOW = 4;
    public const COLOR_BLUE = 5;
    public const COLOR_MAGENTA = 6;
    public const COLOR_CYAN = 7;
    public const COLOR_WHITE = 8;

    /**
     * Define attributes
     */
    public const BOLD = 512;
    public const UNDER_LINE = 1024;
    public const REVERSE = 2048;

    /**
     * @param int $fg
     * @param int $bg
     */
    public function send(int $fg, int $bg): void;
}
