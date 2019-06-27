<?php

namespace MilesChou\Pherm\Concerns;

use OutOfRangeException;

trait CellsTrait
{
    /**
     * @var array Item: [char, fg, bg]
     */
    protected $cells = [];

    /**
     * @param int $x
     * @param int $y
     * @return array
     */
    public function getCell(int $x, int $y): array
    {
        if (!$this->isDisplayable($x, $y)) {
            throw new OutOfRangeException("X: $x, Y: $y is not displayable in terminal");
        }

        return $this->cells[$this->resolveCellPosition($x, $y)];
    }

    /**
     * @return array
     */
    public function getCells(): array
    {
        return $this->cells;
    }

    /**
     * @param array $cell
     * @return static
     */
    public function resetCell(array $cell = [])
    {
        [$x, $y] = $this->size();

        $this->cells = array_fill(0, $x * $y, $cell);

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $cell
     * @param int $fg
     * @param int $bg
     * @return static
     */
    public function writeCell(int $x, int $y, string $cell, int $fg = null, int $bg = null)
    {
        if (!$this->isDisplayable($x, $y)) {
            throw new OutOfRangeException("X: $x, Y: $y is not displayable in terminal");
        }

        $this->cells[$this->resolveCellPosition($x, $y)] = [$cell, $fg, $bg];

        return $this;
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
        [$sizeX, $sizeY] = $this->size();

        return $y * (int)$sizeX + $x;
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function isDisplayable(int $x, int $y): bool
    {
        [$sizeX, $sizeY] = $this->size();

        if ($x < 0 || $x >= $sizeX) {
            return false;
        }

        if ($y < 0 || $y >= $sizeY) {
            return false;
        }

        return true;
    }

    /**
     * @return array [x, y]
     */
    abstract public function size(): array;
}
