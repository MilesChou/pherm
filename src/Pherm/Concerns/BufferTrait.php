<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\CellBuffer;
use OutOfRangeException;

trait BufferTrait
{
    /**
     * @var CellBuffer
     */
    private $cellBuffer;

    /**
     * @param int $x
     * @param int $y
     * @return array
     */
    public function getCell(int $x, int $y): array
    {
        return $this->cellBuffer->get($x, $y);
    }

    /**
     * @return array
     */
    public function getCells(): array
    {
        return $this->cellBuffer->cells;
    }

    /**
     * @return CellBuffer
     */
    public function getCellBuffer(): CellBuffer
    {
        return $this->cellBuffer;
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function isDisplayable(int $x, int $y): bool
    {
        return $this->cellBuffer->inRange($x, $y);
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $string
     * @param int $fg
     * @param int $bg
     * @return static
     */
    public function writeCell(int $x, int $y, string $string, int $fg, int $bg)
    {
        if (!$this->isDisplayable($x, $y)) {
            throw new OutOfRangeException("X: $x, Y: $y is not displayable in terminal");
        }

        $this->cellBuffer->set($x, $y, $string, $fg, $bg);

        return $this;
    }

    public function prepareCellBuffer(): void
    {
        [$width, $height] = $this->size();

        $this->cellBuffer = new CellBuffer($width, $height);
    }

    /**
     * @return array [x, y]
     */
    abstract public function size(): array;
}
