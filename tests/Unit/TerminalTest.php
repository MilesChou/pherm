<?php

namespace Tests\Unit;

use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Contracts\InputStream as InputStreamContract;
use MilesChou\Pherm\Contracts\OutputStream as OutputStreamContract;
use MilesChou\Pherm\Terminal;
use Tests\TestCase;

class TerminalTest extends TestCase
{
    /**
     * @var Terminal
     */
    private $target;

    protected function setUp()
    {
        $this->target = $this->createTerminalInstance();
    }

    /**
     * @test
     */
    public function shouldReturnsTrueIfInputAndOutputAreTTYs(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $this->target->setInput($input)
            ->setOutput($output)
            ->bootstrap();

        $this->assertTrue($this->target->isInteractive());
    }

    /**
     * @test
     */
    public function shouldReturnsFalseIfInputNotTTY(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(true);

        $this->target->setInput($input)
            ->setOutput($output)
            ->bootstrap();

        $this->assertFalse($this->target->isInteractive());
    }

    /**
     * @test
     */
    public function shouldReturnsFalseIfOutputNotTTY(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);
        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $this->target->setInput($input)
            ->setOutput($output)
            ->bootstrap();

        $this->assertFalse($this->target->isInteractive());
    }

    /**
     * @test
     */
    public function shouldReturnsFalseIfInputAndOutputNotTTYs(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);
        $output
            ->method('isInteractive')
            ->willReturn(false);

        $this->target->setInput($input)
            ->setOutput($output)
            ->bootstrap();

        $this->assertFalse($this->target->isInteractive());
    }

    /**
     * @test
     */
    public function shouldThrowsExceptionWhenCallMustBeInteractiveWithInputNotTTY(): void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Input stream is not interactive (non TTY)');

        $input = $this->createMock(InputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $this->target->setInput($input)
            ->bootstrap();

        $this->target->mustBeInteractive();
    }

    /**
     * @test
     */
    public function shouldThrowsExceptionWhenCallMustBeInteractiveWithOutputNotTTY(): void
    {
        $this->expectException(NotInteractiveTerminal::class);
        $this->expectExceptionMessage('Output stream is not interactive (non TTY)');

        $input = $this->createMock(InputStreamContract::class);
        $output = $this->createMock(OutputStreamContract::class);

        $input
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(true);

        $output
            ->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $this->target->setInput($input)
            ->setOutput($output)
            ->bootstrap();

        $this->target->mustBeInteractive();
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenCallClear(): void
    {
        $actual = new BufferedOutput;

        $this->target->setOutput($actual)
            ->bootstrap();

        $this->target->clear();

        $this->assertSame("\033[2J", $actual->fetch());
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenCallClearLine(): void
    {
        $actual = new BufferedOutput;

        $this->target->setOutput($actual)
            ->bootstrap();

        $this->target->clearLine();

        $this->assertSame("\033[2K", $actual->fetch());
    }

    public function testClearDown(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap();
        $target->clearDown();

        $this->assertSame("\033[J", $output->fetch());
    }

    public function testClean(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap();

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

    public function testEnableCursor(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->enableCursor();

        $this->assertSame("\033[?25h", $output->fetch());
    }

    public function testDisableCursor(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->disableCursor();

        $this->assertSame("\033[?25l", $output->fetch());
    }

    public function testMoveCursorToTop(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->move()->top();

        $this->assertSame("\033[0;0H", $output->fetch());
    }

    /**
     * @test
     */
    public function sholudBeOkayWhenCallMoveCursorToEnd(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->move()->end();

        $this->assertSame("\033[24;80H", $output->fetch());
    }

    public function testMoveCursorToRow(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->move()->row(2);

        $this->assertSame("\033[2;0H", $output->fetch());
    }

    public function testMoveCursorToColumn(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->move()->column(10);

        $this->assertSame("\033[0;10H", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectPositionWhenCallMoveCursor(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->move(20, 10);

        $this->assertSame("\033[20;10H", $output->fetch());
    }

    public function testShowAlternateScreen(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->showSecondaryScreen();

        $this->assertSame("\033[?47h", $output->fetch());
    }

    public function testShowMainScreen(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->showPrimaryScreen();

        $this->assertSame("\033[?47l", $output->fetch());
    }

    public function testRead(): void
    {
        $tempStream = fopen('php://temp', 'rb+');
        fwrite($tempStream, 'mystring');
        rewind($tempStream);

        $input = new InputStream($tempStream);
        $output = $this->createMock(OutputStreamContract::class);

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap();

        $this->assertSame('myst', $target->read(4));
        $this->assertSame('ring', $target->read(4));

        fclose($tempStream);
    }

    public function testWriteForwardsToOutput(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap()
            ->write('My awesome string');

        $this->assertSame('My awesome string', $output->fetch());
    }

    public function testGetColourSupport(): void
    {
        $input = $this->createMock(InputStreamContract::class);
        $output = new BufferedOutput;

        $target = new Terminal($input, $output);
        $target->setStty($this->createSttyMock())
            ->bootstrap();

        // Travis terminal supports 8 colours, but just in case
        // in ever changes I'll add the 256 colors possibility too
        $this->assertTrue($target->getColourSupport() === 8 || $target->getColourSupport() === 256);
    }

    /**
     * @test
     */
    public function shouldWriteBackgroundWhenCallBackground(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setStty($this->createSttyMock())
            ->bootstrap();

        $this->target->background(123);

        $this->assertSame("\033[48;5;123m", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteForegroundWhenCallForeground(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setStty($this->createSttyMock())
            ->bootstrap();

        $this->target->foreground(123);

        $this->assertSame("\033[38;5;123m", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteBackgroundAndForegroundWhenCallAttribute(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setStty($this->createSttyMock())
            ->bootstrap();

        $this->target->attribute(48, 62);

        $this->assertContains("\033[38;5;48m", $output->fetch(false));
        $this->assertContains("\033[48;5;62m", $output->fetch(false));
    }
}
