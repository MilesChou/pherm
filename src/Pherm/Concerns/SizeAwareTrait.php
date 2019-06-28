<?php

namespace MilesChou\Pherm\Concerns;

trait SizeAwareTrait
{
    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @return array [x, y]
     */
    public function size(): array
    {
        return [$this->width, $this->height];
    }
}
