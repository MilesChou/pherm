<?php

namespace Tests\Pherm;

use MilesChou\Pherm\Control;
use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Input\StringInput;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Terminal;
use Tests\TestCase;

class TerminalTest extends TestCase
{
    /**
     * @var Terminal
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = $this->createTerminalInstance();
    }

    /**
     * @test
     */
    public function shouldReturnsTrueIfInputAndOutputAreTTYs(): void
    {
        $input = (new StringInput())->mockInteractive(true);
        $output = (new BufferedOutput())->mockInteractive(true);

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
        $input = (new StringInput())->mockInteractive(false);
        $output = (new BufferedOutput())->mockInteractive(true);

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
        $input = (new StringInput())->mockInteractive(true);
        $output = (new BufferedOutput())->mockInteractive(false);

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
        $input = (new StringInput())->mockInteractive(false);
        $output = (new BufferedOutput())->mockInteractive(false);

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

        $input = (new StringInput())->mockInteractive(false);

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

        $input = (new StringInput())->mockInteractive(true);
        $output = (new BufferedOutput())->mockInteractive(false);

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
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->clearDown();

        $this->assertSame("\033[J", $output->fetch());
    }

    public function testEnableCursor(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->enableCursor();

        $this->assertSame("\033[?25h", $output->fetch());
    }

    public function testDisableCursor(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->disableCursor();

        $this->assertSame("\033[?25l", $output->fetch());
    }

    public function testMoveCursorToTop(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->cursor()->top();

        $this->assertSame("\033[1;1H", $output->fetch());
    }

    /**
     * @test
     */
    public function sholudBeOkayWhenCallMoveCursorToEnd(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->cursor()->end();

        $this->assertSame("\033[24;80H", $output->fetch());
    }

    public function testMoveCursorToRow(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->cursor()->row(2);

        $this->assertSame("\033[2;1H", $output->fetch());
    }

    public function testMoveCursorToColumn(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->cursor()->column(10);

        $this->assertSame("\033[1;10H", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectPositionWhenCallMoveCursor(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->moveCursor(10, 20);

        $this->assertSame("\033[20;10H", $output->fetch());
    }

    public function testShowAlternateScreen(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->showSecondaryScreen();

        $this->assertSame("\033[?47h", $output->fetch());
    }

    public function testShowMainScreen(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->showPrimaryScreen();

        $this->assertSame("\033[?47l", $output->fetch());
    }

    public function testRead(): void
    {
        $tempStream = fopen('php://temp', 'rb+');
        fwrite($tempStream, 'mystring');
        rewind($tempStream);

        $this->target->setInput(new InputStream($tempStream))
            ->setOutput(new BufferedOutput())
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap();

        $this->assertSame('myst', $this->target->read(4));
        $this->assertSame('ring', $this->target->read(4));

        fclose($tempStream);
    }

    public function testWriteForwardsToOutput(): void
    {
        $output = new BufferedOutput;

        $this->target->setOutput($output)
            ->setControl(new Control($this->createTTYMock()))
            ->bootstrap()
            ->write('My awesome string');

        $this->assertSame('My awesome string', $output->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteBackgroundAndForegroundWhenCallAttribute(): void
    {
        $this->target->setControl(new Control($this->createTTYMock()))
            ->bootstrap();

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->target->attribute(48, 62);

        $this->assertStringContainsString("\033[38;5;48m", $output->fetch(false));
        $this->assertStringContainsString("\033[48;5;62m", $output->fetch(false));
    }

    /**
     * @test
     */
    public function shouldResetAllCell(): void
    {
        $this->target->bootstrap()->clear();

        // 80 * 24 = 1920
        $this->assertCount(1920, $this->target->getCells());
        $this->assertSame([' ', null, null], $this->target->getCell(1, 1));
        $this->assertSame([' ', null, null], $this->target->getCell(80, 24));
    }
}
