<?php

namespace MilesChou\Pherm;

use OutOfRangeException;

class CellBuffer
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var array [char, fg, bg]
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
        array_walk($this->cells, static function (&$v) use ($fg, $bg) {
            $v = [' ', $fg, $bg];
        });
    }

    /**
     * @param int $x
     * @param int $y
     * @return array
     */
    public function get(int $x, int $y): array
    {
        $this->checkRange($x, $y);

        return $this->cells[$this->resolveCellPosition($x, $y)];
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
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

    /**
     * @param int $width
     * @param int $height
     */
    public function resize(int $width, int $height): void
    {
        if ($this->width === $width && $this->height === $height) {
            return;
        }

        $oldWidth = $this->width;
        $oldHeight = $this->height;
        $oldCells = $this->cells;

        $this->init($width, $height);

        $minWidth = min($width, $oldWidth);
        $minHeight = min($height, $oldHeight);

        for ($i = 0; $i < $minHeight; $i++) {
            $src = array_slice($oldCells, $i * $oldWidth, $minWidth);
            array_splice($this->cells, $i * $width, $minWidth, $src);
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $char
     * @param int $fg
     * @param int $bg
     * @return static
     */
    public function set(int $x, int $y, string $char, int $fg, int $bg)
    {
        return $this->setCell($x, $y, [$char, $fg, $bg]);
    }

    /**
     * @param int $x
     * @param int $y
     * @param array $cell
     * @return static
     */
    public function setCell(int $x, int $y, array $cell)
    {
        $this->checkRange($x, $y);

        $this->cells[$this->resolveCellPosition($x, $y)] = $cell;

        return $this;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    protected function checkRange(int $x, int $y): bool
    {
        if ($x < 1 || $x > $this->width) {
            throw new OutOfRangeException("X: $x is out of range in cell buffer");
        }

        if ($y < 1 || $y > $this->height) {
            throw new OutOfRangeException("Y: $y is out of range in cell buffer");
        }

        return true;
    }

    /**
     * Return the position in 1-axis array
     *
     * @param int $x
     * @param int $y
     * @return int
     */
    protected function resolveCellPosition(int $x, int $y): int
    {
        return ($y - 1) * $this->width + ($x - 1);
    }
}
