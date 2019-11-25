<?php

namespace Tests\Pherm\Concerns;

use MilesChou\Pherm\Concerns\BufferTrait;
use OutOfRangeException;
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

        $this->target->prepareCellBuffer();
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
     */
    public function shouldThrowExceptionWhenGetCellWithOutOfRange(): void
    {
        $this->expectException(OutOfRangeException::class);

        $this->target->getCell(11, 20);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenWriteCellWithOutOfRange(): void
    {
        $this->expectException(OutOfRangeException::class);

        $this->target->writeCell(10, 21, ' ', 0, 0);
    }
}
