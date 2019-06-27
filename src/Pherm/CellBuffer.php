<?php

namespace MilesChou\Pherm;

class CellBuffer
{
    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var array
     */
    public $cells = [];

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->init($width, $height);
    }

    /**
     * @param int $fg
     * @param int $bg
     */
    public function clear(int $fg, int $bg): void
    {
        array_walk($this->cells, function (&$v) use ($fg, $bg) {
            $v = [' ', $fg, $bg];
        });
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function init(int $width, int $height): void
    {
        $this->width = $width;
        $this->height = $height;
        $this->cells = array_fill(0, $width * $height, []);
    }
}
