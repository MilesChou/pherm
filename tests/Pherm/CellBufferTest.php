<?php

namespace Tests\Unit;

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

        $this->assertSame(10, $target->width);
        $this->assertSame(20, $target->height);
        $this->assertCount(200, $target->cells);
        $this->assertSame([], $target->cells[0]);
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenInitial(): void
    {
        $target = new CellBuffer(10, 20);

        $target->init(30, 40);

        $this->assertSame(30, $target->width);
        $this->assertSame(40, $target->height);
        $this->assertCount(1200, $target->cells);
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenClear(): void
    {
        $target = new CellBuffer(10, 20);

        $target->clear(15, 0);

        $this->assertSame([' ', 15, 0], $target->cells[0]);
    }
}
