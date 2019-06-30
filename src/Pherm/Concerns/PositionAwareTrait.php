<?php

namespace MilesChou\Pherm\Concerns;

trait PositionAwareTrait
{
    /**
     * @var int
     */
    private $x = 1;

    /**
     * @var int
     */
    private $y = 1;

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return [$this->x, $this->y];
    }

    /**
     * @param int $x
     * @param int $y
     * @return static
     */
    public function setPosition(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;

        return $this;
    }
}
