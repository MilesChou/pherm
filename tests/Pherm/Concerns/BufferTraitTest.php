<?php

namespace Tests\Pherm\Concerns;

use MilesChou\Pherm\Concerns\BufferTrait;
use Tests\TestCase;

class BufferTraitTest extends TestCase
{
    /**
     * @var BufferTrait|\PHPUnit\Framework\MockObject\MockObject
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = $this->getMockForTrait(BufferTrait::class);
        $this->target->method('size')
            ->willReturn([10, 20]);

        $this->target->prepareBuffer();
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldWriteAndGetAtSameCell(): void
    {
        $this->target->writeCell(5, 5, 'ok', 50, 30);

        $this->assertSame(['ok', 50, 30], $this->target->getCell(5, 5));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function shouldThrowExceptionWhenGetCellWithOutOfRange(): void
    {
        $this->target->getCell(11, 20);
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function shouldThrowExceptionWhenWriteCellWithOutOfRange(): void
    {
        $this->target->writeCell(10, 21, ' ', 0, 0);
    }
}
