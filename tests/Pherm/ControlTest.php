<?php

namespace Tests\Pherm;

use LogicException;
use MilesChou\Pherm\Contracts\Control as ControlContract;
use MilesChou\Pherm\Control;
use MilesChou\Pherm\TTY;
use OutOfRangeException;
use Tests\TestCase;

class ControlTest extends TestCase
{
    /**
     * @var Control
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = new Control(new TTY());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCallMagicMethodWithNonExistName(): void
    {
        $this->expectException(OutOfRangeException::class);

        $this->target->notExist;
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCallExistMethodWithSameName(): void
    {
        $this->expectException(LogicException::class);

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
     */
    public function shouldThrowExceptionWhenSetProperty(): void
    {
        $this->expectException(LogicException::class);

        $this->target->whatever = 'whatever';
    }
}
