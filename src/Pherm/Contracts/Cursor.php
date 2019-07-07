<?php

namespace MilesChou\Pherm\Contracts;

interface Cursor
{
    /**
     * @param int $x
     * @param int $y
     * @return string
     */
    public function move(int $x, int $y): string;
}
