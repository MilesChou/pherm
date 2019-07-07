<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\ControlTrait;

class Control
{
    use ControlTrait;

    /**
     * @param array $overwrite
     */
    public function __construct($overwrite = [])
    {
        $this->overwrite($overwrite);
    }
}
