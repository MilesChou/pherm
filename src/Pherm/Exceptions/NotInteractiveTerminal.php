<?php

namespace MilesChou\Pherm\Exceptions;

use RuntimeException;

class NotInteractiveTerminal extends RuntimeException
{
    public static function inputNotInteractive(): self
    {
        return new self('Input stream is not interactive (non TTY)');
    }

    public static function outputNotInteractive(): self
    {
        return new self('Output stream is not interactive (non TTY)');
    }
}
