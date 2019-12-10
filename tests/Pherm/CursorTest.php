<?php

namespace Tests\Pherm;

use MilesChou\Pherm\CursorHelper;
use MilesChou\Pherm\TTY;
use OverflowException;
use Tests\TestCase;

class CursorTest extends TestCase
{
    /**
     * @var CursorHelper
     */
    private $target;

    public function overflowCase()
    {
        return [
            [0, 5],
            [5, 0],
            [81, 5],
            [5, 25],
        ];
    }

    protected function setUp(): void
    {
        // col 80, row 24
        $this->target = $this->createTerminalInstance()->bootstrap()->cursor();
    }

    /**
     * @test
     * @dataProvider overflowCase
     */
    public function shouldThrowWriteCorrectStringWhenCallMove($x, $y): void
    {
        $this->expectException(OverflowException::class);

        $this->target->move($x, $y);
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMove(): void
    {
        $terminal = $this->target->move(10, 20);

        $this->assertSame("\033[20;10H", $terminal->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveBottomAndAliasMethod(): void
    {
        $this->assertSame("\033[24;10H", $this->target->bottom(10)->getOutput()->fetch());
        $this->assertSame("\033[24;1H", $this->target->bottom()->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveCenterAndAliasMethod(): void
    {
        $this->assertSame("\033[12;40H", $this->target->center()->getOutput()->fetch());
        $this->assertSame("\033[15;35H", $this->target->center(-5, 3)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveEndAndAliasMethod(): void
    {
        $this->assertSame("\033[24;80H", $this->target->end()->getOutput()->fetch());
        $this->assertSame("\033[20;78H", $this->target->end(2, 4)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveMiddleAndAliasMethod(): void
    {
        $this->assertSame("\033[12;1H", $this->target->middle()->getOutput()->fetch());
        $this->assertSame("\033[12;6H", $this->target->middle(6)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveRowAndAliasMethod(): void
    {
        $this->assertSame("\033[4;1H", $this->target->row(4)->getOutput()->fetch());
        $this->assertSame("\033[8;1H", $this->target->row(8)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveTopAndAliasMethod(): void
    {
        $this->assertSame("\033[1;1H", $this->target->top()->getOutput()->fetch());
        $this->assertSame("\033[1;5H", $this->target->top(5)->getOutput()->fetch());
    }
}
