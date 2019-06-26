<?php

namespace Tests\Unit;

use InvalidArgumentException;
use MilesChou\Pherm\InputCharacter;
use MilesChou\Pherm\NonCanonicalReader;
use MilesChou\Pherm\Contracts\Terminal;
use Tests\TestCase;

class NonCanonicalReaderTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Control "w" does not exist
     */
    public function shouldThrowExceptionWhenMappingAddedForNonControlCharacter(): void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminalReader = new NonCanonicalReader($terminal);
        $terminalReader->addControlMapping('p', 'w');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Control "w" does not exist
     */
    public function shouldThrowExceptionWhenMappingsAddedForNonControlCharacter(): void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminalReader = new NonCanonicalReader($terminal);
        $terminalReader->addControlMappings(['p' => 'w']);
    }

    public function testCustomMappingToUpControl(): void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal
            ->expects($this->once())
            ->method('read')
            ->with(4)
            ->willReturn('w');

        $terminalReader = new NonCanonicalReader($terminal);
        $terminalReader->addControlMapping('w', InputCharacter::UP);

        $char = $terminalReader->readCharacter();

        $this->assertTrue($char->isControl());
        $this->assertSame('UP', $char->getControl());
        $this->assertSame("\033[A", $char->get());
    }

    public function testReadNormalCharacter(): void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal
            ->expects($this->once())
            ->method('read')
            ->with(4)
            ->willReturn('w');

        $terminalReader = new NonCanonicalReader($terminal);

        $char = $terminalReader->readCharacter();

        $this->assertFalse($char->isControl());
        $this->assertSame('w', $char->get());
    }

    public function testReadControlCharacter()
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal
            ->expects($this->once())
            ->method('read')
            ->with(4)
            ->willReturn("\n");

        $terminalReader = new NonCanonicalReader($terminal);

        $char = $terminalReader->readCharacter();

        $this->assertTrue($char->isControl());
        $this->assertSame('ENTER', $char->getControl());
        $this->assertSame("\n", $char->get());
    }
}
