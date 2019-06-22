<?php

namespace Tests;

use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Contracts\InputStream as InputStreamContract;
use MilesChou\Pherm\Contracts\OutputStream as OutputStreamContract;
use MilesChou\Pherm\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    public function testIsInteractiveReturnsTrueIfInputAndOutputAreTTYs() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $this->assertTrue($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfInputNotTTY() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(true);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $this->assertFalse($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfOutputNotTTY() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $this->assertFalse($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfInputAndOutputNotTTYs() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(false);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $this->assertFalse($target->isInteractive());
    }

    public function testMustBeInteractiveThrowsExceptionIfInputNotTTY() : void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Input stream is not interactive (non TTY)');

        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $target->mustBeInteractive();
    }

    public function testMustBeInteractiveThrowsExceptionIfOutputNotTTY() : void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Output stream is not interactive (non TTY)');

        $input  = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $target->mustBeInteractive();
    }

    public function testClear() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->clear();

        $this->assertSame("\033[2J", $output->fetch());
    }

    public function testClearLine() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->clearLine();

        $this->assertSame("\033[2K", $output->fetch());
    }

    public function testClearDown() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->clearDown();

        $this->assertSame("\033[J", $output->fetch());
    }

    public function testClean() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $rf = new \ReflectionObject($target);
        $rp = $rf->getProperty('width');
        $rp->setAccessible(true);
        $rp->setValue($target, 23);
        $rp = $rf->getProperty('height');
        $rp->setAccessible(true);
        $rp->setValue($target, 2);

        $target->clean();

        $this->assertSame("\033[0;0H\033[2K\033[1;0H\033[2K\033[2;0H\033[2K", $output->fetch());
    }

    public function testEnableCursor() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->enableCursor();

        $this->assertSame("\033[?25h", $output->fetch());
    }

    public function testDisableCursor() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->disableCursor();

        $this->assertSame("\033[?25l", $output->fetch());
    }

    public function testMoveCursorToTop() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->moveCursorToTop();

        $this->assertSame("\033[0;0H", $output->fetch());
    }

    public function testMoveCursorToRow() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->moveCursorToRow(2);

        $this->assertSame("\033[2;0H", $output->fetch());
    }

    public function testMoveCursorToColumn() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->moveCursorToColumn(10);

        $this->assertSame("\033[10C", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectPositionWhenCallMoveCursor() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->moveCursor(10, 20);

        $this->assertSame("\033[20;10H", $output->fetch());
    }

    public function testShowAlternateScreen() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->showSecondaryScreen();

        $this->assertSame("\033[?47h", $output->fetch());
    }

    public function testShowMainScreen() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->showPrimaryScreen();

        $this->assertSame("\033[?47l", $output->fetch());
    }

    public function testRead() : void
    {
        $tempStream = fopen('php://temp', 'rb+');
        fwrite($tempStream, 'mystring');
        rewind($tempStream);

        $input  = new InputStream($tempStream);
        $output = $this->createMock(OutputStreamContract::class);

        $target = new Terminal($input, $output);
        $target->bootstrap();

        $this->assertSame('myst', $target->read(4));
        $this->assertSame('ring', $target->read(4));

        fclose($tempStream);
    }

    public function testWriteForwardsToOutput() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();
        $target->write('My awesome string');

        $this->assertSame('My awesome string', $output->fetch());
    }

    public function testGetColourSupport() : void
    {
        $input  = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->bootstrap();

        // Travis terminal supports 8 colours, but just in case
        // in ever changes I'll add the 256 colors possibility too
        $this->assertTrue($target->getColourSupport() === 8 || $target->getColourSupport() === 256);
    }
}
