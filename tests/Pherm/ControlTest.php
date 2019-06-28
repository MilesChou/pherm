<?php

namespace Tests\Pherm;

use LogicException;
use MilesChou\Pherm\Control;
use OutOfRangeException;
use Tests\TestCase;

class ControlTest extends TestCase
{
    /**
     * @var Control
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Control();
    }

    /**
     * @test
     * @expectedException OutOfRangeException
     */
    public function shouldThrowExceptionWhenCallMagicMethodWithNonExistName(): void
    {
        $this->target->notExist;
    }

    /**
     * @test
     * @expectedException LogicException
     */
    public function shouldThrowExceptionWhenCallExistMethodWithSameName(): void
    {
        $this->target->cup;
    }

    /**
     * @test
     */
    public function shouldGetConstArrowControlSeqWhenCallMagicMethod(): void
    {
        $this->assertSame(Control::CONTROL_SEQUENCES['CUU'], $this->target->cuu);
    }

    /**
     * @test
     */
    public function shouldOverwriteConstArrowControlSeqWhenCallMagicMethod(): void
    {
        $target = new Control([
            'CUU' => 'whatever',
        ]);

        $this->assertSame('whatever', $target->cuu);
    }

    /**
     * @test
     */
    public function shouldGetConstNullControlCharWhenCallMagicMethod(): void
    {
        $this->assertSame(Control::CONTROL_CHARACTER['NUL'], $this->target->nul);
    }

    /**
     * @test
     */
    public function shouldOverwriteConstNullControlCharWhenCallMagicMethod(): void
    {
        $target = new Control([
            'NUL' => 'whatever',
        ]);

        $this->assertSame('whatever', $target->nul);
    }

    /**
     * @test
     */
    public function shouldReturnCustomControlCharWhenCallMagicMethod(): void
    {
        $target = new Control([
            'CUSTOM' => 'whatever',
        ]);

        $this->assertSame('whatever', $target->custom);
    }

    /**
     * @test
     */
    public function shouldCombineStrCallCup(): void
    {
        $this->assertSame("\033[1;2H", $this->target->cup(1, 2));
    }

    /**
     * @test
     */
    public function shouldCombineStrCallHvp(): void
    {
        $this->assertSame("\033[2;3f", $this->target->hvp(2, 3));
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function shouldThrowExceptionWhenSetProperty(): void
    {
        $this->target->whatever = 'whatever';
    }
}
