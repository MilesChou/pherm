<?php

namespace MilesChou\Pherm\Concerns;

use InvalidArgumentException;

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
     * @param int $width
     * @param int $height
     * @return static
     * @throws InvalidArgumentException
     */
    public function setSize(int $width, int $height)
    {
        if ($width < 1) {
            throw new InvalidArgumentException('Width must > 0');
        }

        if ($height < 1) {
            throw new InvalidArgumentException('Height must > 0');
        }

        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * @return array<int>
     */
    public function size(): array
    {
        return [$this->width(), $this->height()];
    }
}
