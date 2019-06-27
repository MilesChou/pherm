<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\CellBuffer;
use OutOfRangeException;

trait BufferTrait
{
    /**
     * @var CellBuffer
     */
    private $frontBuffer;

    /**
     * @var CellBuffer
     */
    private $backBuffer;

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

        return $this->backBuffer->cells[$this->resolveCellPosition($x, $y)];
    }

    /**
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->backBuffer->cells;
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function isDisplayable(int $x, int $y): bool
    {
        [$sizeX, $sizeY] = $this->size();

        if ($x < 1 || $x > $sizeX) {
            return false;
        }

        if ($y < 1 || $y > $sizeY) {
            return false;
        }

        return true;
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

        $this->backBuffer->cells[$this->resolveCellPosition($x, $y)] = [$cell, $fg, $bg];

        return $this;
    }

    public function prepareBuffer(): void
    {
        [$width, $height] = $this->size();

        $this->backBuffer = new CellBuffer($width, $height);
        $this->frontBuffer = new CellBuffer($width, $height);
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

        return ($y - 1) * (int)$sizeX + ($x - 1);
    }

    /**
     * @return array [x, y]
     */
    abstract public function size(): array;
}
