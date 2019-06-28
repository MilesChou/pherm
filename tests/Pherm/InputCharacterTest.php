<?php

namespace Tests\Pherm;

use MilesChou\Pherm\InputCharacter;
use Tests\TestCase;

class InputCharacterTest extends TestCase
{
    public function testWhenCharacterIsAControl(): void
    {
        $char = new InputCharacter("\n");

        $this->assertTrue($char->isControl());
        $this->assertTrue($char->isHandledControl());
        $this->assertFalse($char->isNotControl());
        $this->assertSame('ENTER', $char->getControl());
        $this->assertSame("\n", $char->get());
        $this->assertSame("\n", $char->__toString());
    }

    public function testWhenCharacterIsNotAControl(): void
    {
        $char = new InputCharacter('p');

        $this->assertFalse($char->isControl());
        $this->assertFalse($char->isHandledControl());
        $this->assertTrue($char->isNotControl());
        $this->assertSame('p', $char->get());
        $this->assertSame('p', $char->__toString());
    }

    public function testExceptionIsThrownIfGetControlCalledWhenNotAControl(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Character "p" is not a control');

        $char = new InputCharacter('p');
        $char->getControl();
    }

    public function testGetControls(): void
    {
        $this->assertSame(
            [
                'UP',
                'DOWN',
                'RIGHT',
                'LEFT',
                'CTRLA',
                'CTRLB',
                'CTRLE',
                'CTRLF',
                'BACKSPACE',
                'CTRLW',
                'ENTER',
                'TAB',
                'ESC',
            ],
            InputCharacter::getControls()
        );
    }

    public function testFromControlNameThrowsExceptionIfControlDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Control "w" does not exist');

        InputCharacter::fromControlName('w');
    }

    public function testFromControlName(): void
    {
        $char = InputCharacter::fromControlName(InputCharacter::UP);

        $this->assertTrue($char->isControl());
        $this->assertSame('UP', $char->getControl());
        $this->assertSame("\033[A", $char->get());
    }

    public function testControlExists(): void
    {
        $this->assertTrue(InputCharacter::controlExists(InputCharacter::UP));
        $this->assertFalse(InputCharacter::controlExists('w'));
    }

    public function testIsControlOnNotExplicitlyHandledControls(): void
    {
        $char = new InputCharacter("\016"); //ctrl + p (I think)

        $this->assertTrue($char->isControl());
        $this->assertFalse($char->isHandledControl());

        $char = new InputCharacter("\021"); //ctrl + u (I think)

        $this->assertTrue($char->isControl());
        $this->assertFalse($char->isHandledControl());
    }

    public function testUnicodeCharacter(): void
    {
        $char = new InputCharacter('ß');

        $this->assertFalse($char->isControl());
        $this->assertFalse($char->isHandledControl());
        $this->assertTrue($char->isNotControl());
        $this->assertSame('ß', $char->get());
        $this->assertSame('ß', $char->__toString());
    }
}
