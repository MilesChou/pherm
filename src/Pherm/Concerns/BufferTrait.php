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
        return $this->backBuffer->get($x, $y);
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
        return $this->backBuffer->inRange($x, $y);
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

        $this->backBuffer->set($x, $y, $string, $fg, $bg);

        return $this;
    }

    public function prepareBuffer(): void
    {
        [$width, $height] = $this->size();

        $this->backBuffer = new CellBuffer($width, $height);
        $this->frontBuffer = new CellBuffer($width, $height);
    }

    /**
     * @return array [x, y]
     */
    abstract public function size(): array;
}
