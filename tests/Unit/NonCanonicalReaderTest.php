<?php

namespace Tests\Unit;

use InvalidArgumentException;
use MilesChou\Pherm\InputCharacter;
use MilesChou\Pherm\NonCanonicalReader;
use MilesChou\Pherm\Contracts\Terminal;
use PHPUnit\Framework\TestCase;

class NonCanonicalReaderTest extends TestCase
{
    public function testExceptionIsThrownIfMappingAddedForNonControlCharacter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Control "w" does not exist');

        $terminal = $this->createMock(Terminal::class);
        $terminalReader = new NonCanonicalReader($terminal);
        $terminalReader->addControlMapping('p', 'w');
    }

    public function testExceptionIsThrownIfMappingsAddedForNonControlCharacter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Control "w" does not exist');

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
