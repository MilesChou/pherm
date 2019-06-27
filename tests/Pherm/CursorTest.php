<?php

namespace Tests\Unit;

use MilesChou\Pherm\Contracts\Cursor;
use Tests\TestCase;

class CursorTest extends TestCase
{
    /**
     * @var Cursor
     */
    private $target;

    protected function setUp()
    {
        // col 80, row 24
        $this->target = $this->createTerminalInstance()->bootstrap()->cursor();
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
        $this->assertSame("\033[24;10H", $this->target->moveBottom(10)->getOutput()->fetch());
        $this->assertSame("\033[24;0H", $this->target->bottom()->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveCenterAndAliasMethod(): void
    {
        $this->assertSame("\033[12;40H", $this->target->moveCenter()->getOutput()->fetch());
        $this->assertSame("\033[15;35H", $this->target->center(-5, 3)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveEndAndAliasMethod(): void
    {
        $this->assertSame("\033[24;80H", $this->target->moveEnd()->getOutput()->fetch());
        $this->assertSame("\033[20;78H", $this->target->end(2, 4)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveMiddleAndAliasMethod(): void
    {
        $this->assertSame("\033[12;0H", $this->target->moveMiddle()->getOutput()->fetch());
        $this->assertSame("\033[12;6H", $this->target->middle(6)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveRowAndAliasMethod(): void
    {
        $this->assertSame("\033[4;0H", $this->target->moveRow(4)->getOutput()->fetch());
        $this->assertSame("\033[8;0H", $this->target->row(8)->getOutput()->fetch());
    }

    /**
     * @test
     */
    public function shouldWriteCorrectStringWhenCallMoveTopAndAliasMethod(): void
    {
        $this->assertSame("\033[0;0H", $this->target->moveTop()->getOutput()->fetch());
        $this->assertSame("\033[0;5H", $this->target->top(5)->getOutput()->fetch());
    }
}
