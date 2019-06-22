<?php

namespace Tests\Exception;

use MilesChou\Pherm\Exception\NotInteractiveTerminal;
use PHPUnit\Framework\TestCase;

class NotInteractiveTerminalTest extends TestCase
{
    public function testInputNotInteractive() : void
    {
        $e = NotInteractiveTerminal::inputNotInteractive();

        $this->assertEquals('Input stream is not interactive (non TTY)', $e->getMessage());
    }

    public function testOutputNotInteractive() : void
    {
        $e = NotInteractiveTerminal::outputNotInteractive();

        $this->assertEquals('Output stream is not interactive (non TTY)', $e->getMessage());
    }
}
