<?php

namespace MilesChou\Pherm\Contracts;

use MilesChou\Pherm\CellBuffer;

/**
 * Interface for send attribute
 */
interface Renderer
{
    /**
     * @param CellBuffer $buffer
     */
    public function renderBuffer(CellBuffer $buffer): void;
}
