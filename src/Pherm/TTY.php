<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\TTYTrait;
use MilesChou\Pherm\Contracts\TTY as TTYContract;

class TTY implements TTYContract
{
    use TTYTrait;
}
