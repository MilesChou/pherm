<?php

namespace MilesChou\Pherm\Concerns;

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
        return $this->cells[static::resolveCellPosition($x, $y)];
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
        $this->cells[static::resolveCellPosition($x, $y)] = [$cell, $fg, $bg];

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
     * @return array [x, y]
     */
    abstract public function size(): array;
}
