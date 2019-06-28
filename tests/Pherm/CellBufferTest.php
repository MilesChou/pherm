<?php

namespace Tests\Pherm;

use MilesChou\Pherm\CellBuffer;
use Tests\TestCase;

class CellBufferTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeOkayWhenConstruct(): void
    {
        $target = new CellBuffer(10, 20);

        $this->assertSame(10, $target->width());
        $this->assertSame(20, $target->height());
        $this->assertCount(200, $target->cells);
        $this->assertSame([' ', null, null], $target->cells[0]);
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenInitial(): void
    {
        $target = new CellBuffer(10, 20);

        $target->init(30, 40);

        $this->assertSame(30, $target->width());
        $this->assertSame(40, $target->height());
        $this->assertCount(1200, $target->cells);
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenClear(): void
    {
        $target = new CellBuffer(10, 20);

        $target->clear();

        $this->assertSame([' ', null, null], $target->cells[0]);
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenResizeToSmall(): void
    {
        $target = new CellBuffer(10, 20);
        $target->set(5, 15, 'a', 3, 4);

        $target->resize(5, 15);

        $this->assertSame(['a', 3, 4], $target->get(5, 15));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenResizeToLarge(): void
    {
        $target = new CellBuffer(5, 15);
        $target->set(5, 15, 'b', 5, 6);

        $target->resize(10, 20);

        $this->assertSame(['b', 5, 6], $target->get(5, 15));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenResizeToSame(): void
    {
        $target = new CellBuffer(5, 15);
        $target->set(5, 15, 'c', 7, 8);

        $target->resize(5, 15);

        $this->assertSame(['c', 7, 8], $target->get(5, 15));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function shouldThrowExceptionWhenGetOutOfRange(): void
    {
        $target = new CellBuffer(5, 15);
        $target->get(6, 15);
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function shouldThrowExceptionWhenSetOutOfRange(): void
    {
        $target = new CellBuffer(5, 15);
        $target->set(5, 16, 'a', 3, 4);
    }
}
