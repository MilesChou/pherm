<?php

namespace Tests\Exception;

use MilesChou\Pherm\Exception\NotInteractiveTerminal;
use PHPUnit\Framework\TestCase;

class NotInteractiveTerminalTest extends TestCase
{
    public function testInputNotInteractive() : void
    {
        $e = NotInteractiveTerminal::inputNotInteractive();

        self::assertEquals('Input stream is not interactive (non TTY)', $e->getMessage());
    }

    public function testOutputNotInteractive() : void
    {
        $e = NotInteractiveTerminal::outputNotInteractive();

        self::assertEquals('Output stream is not interactive (non TTY)', $e->getMessage());
    }
}
