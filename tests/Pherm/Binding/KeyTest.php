<?php

namespace Tests\Pherm\Binding;

use MilesChou\Pherm\Binding\Key;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Terminal;
use Tests\TestCase;

class KeyTest extends TestCase
{
    /**
     * @var Key
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = $this->createTerminalInstance()->keyBinding();
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldBeHandleUsingSet(): void
    {
        $this->target->set('q', function (Terminal $terminal) {
            $this->assertInstanceOf(Terminal::class, $terminal);
            $this->assertTrue(true, 'The handler did not handled');
        });

        $this->target->handle('q');
    }

    /**
     * @test
     */
    public function shouldBeHandleUsingSetAll(): void
    {
        $this->target->setAll([
            'q' => function (Terminal $terminal) {
                $this->assertInstanceOf(Terminal::class, $terminal);
                $this->assertTrue(true, 'The handler did not handled');
            },
        ]);

        $this->target->handle('q');
    }

    /**
     * @test
     */
    public function shouldUseDefaultHandler(): void
    {
        $this->target->set('q');
        $this->target->handle('q');

        /** @var BufferedOutput $output */
        $output = $this->target->getTerminal()->getOutput();

        $this->assertSame('q', $output->fetch());
    }
}
