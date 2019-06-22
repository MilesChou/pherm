<?php

namespace MilesChou\Pherm\Concerns;

/**
 * Screen size of terminal
 */
trait Size
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    public function width(): int
    {
        return $this->width ?: $this->width = (int)exec('tput cols');
    }

    public function height(): int
    {
        return $this->height ?: $this->height = (int)exec('tput lines');
    }
}
