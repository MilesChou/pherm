<?php

namespace Tests;

use MilesChou\Pherm\Exception\NotInteractiveTerminal;
use MilesChou\Pherm\IO\BufferedOutput;
use MilesChou\Pherm\IO\InputStream;
use MilesChou\Pherm\IO\OutputStream;
use MilesChou\Pherm\IO\ResourceInputStream;
use MilesChou\Pherm\UnixTerminal;
use PHPUnit\Framework\TestCase;

class UnixTerminalTest extends TestCase
{
    public function testIsInteractiveReturnsTrueIfInputAndOutputAreTTYs() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $target = new UnixTerminal($input, $output);

        $this->assertTrue($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfInputNotTTY() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(true);

        $target = new UnixTerminal($input, $output);

        $this->assertFalse($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfOutputNotTTY() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new UnixTerminal($input, $output);

        $this->assertFalse($target->isInteractive());
    }

    public function testIsInteractiveReturnsFalseIfInputAndOutputNotTTYs() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(false);

        $target = new UnixTerminal($input, $output);

        $this->assertFalse($target->isInteractive());
    }

    public function testMustBeInteractiveThrowsExceptionIfInputNotTTY() : void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Input stream is not interactive (non TTY)');

        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new UnixTerminal($input, $output);
        $target->mustBeInteractive();
    }

    public function testMustBeInteractiveThrowsExceptionIfOutputNotTTY() : void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Output stream is not interactive (non TTY)');

        $input  = $this->createMock(InputStream::class);
        $output = $this->createMock(OutputStream::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $target = new UnixTerminal($input, $output);
        $target->mustBeInteractive();
    }

    public function testClear() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->clear();

        $this->assertSame("\033[2J", $output->fetch());
    }

    public function testClearLine() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->clearLine();

        $this->assertSame("\033[2K", $output->fetch());
    }

    public function testClearDown() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->clearDown();

        $this->assertSame("\033[J", $output->fetch());
    }

    public function testClean() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $rf = new \ReflectionObject($terminal);
        $rp = $rf->getProperty('width');
        $rp->setAccessible(true);
        $rp->setValue($terminal, 23);
        $rp = $rf->getProperty('height');
        $rp->setAccessible(true);
        $rp->setValue($terminal, 2);

        $terminal->clean();

        $this->assertSame("\033[0;0H\033[2K\033[1;0H\033[2K\033[2;0H\033[2K", $output->fetch());
    }

    public function testEnableCursor() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->enableCursor();

        $this->assertSame("\033[?25h", $output->fetch());
    }

    public function testDisableCursor() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->disableCursor();

        $this->assertSame("\033[?25l", $output->fetch());
    }

    public function testMoveCursorToTop() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->moveCursorToTop();

        $this->assertSame("\033[H", $output->fetch());
    }

    public function testMoveCursorToRow() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->moveCursorToRow(2);

        $this->assertSame("\033[2;0H", $output->fetch());
    }

    public function testMoveCursorToColumn() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->moveCursorToColumn(10);

        $this->assertSame("\033[10C", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectPositionWhenCallMoveCursor() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->moveCursor(10, 20);

        $this->assertSame("\033[20;10H", $output->fetch());
    }

    public function testShowAlternateScreen() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->showSecondaryScreen();

        $this->assertSame("\033[?47h", $output->fetch());
    }

    public function testShowMainScreen() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->showPrimaryScreen();

        $this->assertSame("\033[?47l", $output->fetch());
    }

    public function testRead() : void
    {
        $tempStream = fopen('php://temp', 'rb+');
        fwrite($tempStream, 'mystring');
        rewind($tempStream);

        $input  = new ResourceInputStream($tempStream);
        $output = $this->createMock(OutputStream::class);

        $terminal = new UnixTerminal($input, $output);

        $this->assertSame('myst', $terminal->read(4));
        $this->assertSame('ring', $terminal->read(4));

        fclose($tempStream);
    }

    public function testWriteForwardsToOutput() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);
        $terminal->write('My awesome string');

        $this->assertSame('My awesome string', $output->fetch());
    }

    public function testGetColourSupport() : void
    {
        $input  = $this->createMock(InputStream::class);
        $output = new BufferedOutput;

        $terminal = new UnixTerminal($input, $output);

        // Travis terminal supports 8 colours, but just in case
        // in ever changes I'll add the 256 colors possibility too
        $this->assertTrue($terminal->getColourSupport() === 8 || $terminal->getColourSupport() === 256);
    }
}
