<?php

namespace Tests\Pherm;

use LogicException;
use MilesChou\Pherm\Control;
use MilesChou\Pherm\Contracts\Control as ControlContract;
use MilesChou\Pherm\TTY;
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
        $this->target = new Control(new TTY());
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
        $this->assertSame(ControlContract::CONTROL_SEQUENCES['CUU'], $this->target->cuu);
    }

    /**
     * @test
     */
    public function shouldOverwriteConstArrowControlSeqWhenCallMagicMethod(): void
    {
        $this->target->overwrite([
            'CUU' => 'whatever',
        ]);

        $this->assertSame('whatever', $this->target->cuu);
    }

    /**
     * @test
     */
    public function shouldGetConstNullControlCharWhenCallMagicMethod(): void
    {
        $this->assertSame(ControlContract::CONTROL_CHARACTER['NUL'], $this->target->nul);
    }

    /**
     * @test
     */
    public function shouldOverwriteConstNullControlCharWhenCallMagicMethod(): void
    {
        $this->target->overwrite([
            'NUL' => 'whatever',
        ]);

        $this->assertSame('whatever', $this->target->nul);
    }

    /**
     * @test
     */
    public function shouldReturnCustomControlCharWhenCallMagicMethod(): void
    {
        $this->target->overwrite([
            'CUSTOM' => 'whatever',
        ]);

        $this->assertSame('whatever', $this->target->custom);
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
