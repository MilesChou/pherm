<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\CellBuffer;
use MilesChou\Pherm\Contracts\Attribute;
use MilesChou\Pherm\Output\Attributes\Color256;
use OutOfRangeException;

trait AttributeTrait
{
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var int|null
     */
    private $lastFg = Attribute::INVALID;

    /**
     * @var int|null
     */
    private $lastBg = Attribute::INVALID;

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute
    {
        if (null === $this->attribute) {
            $this->attribute = new Color256();
        }

        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute): void
    {
        $this->attribute = $attribute;
    }
}
