<?php

namespace Tests\Pherm\Exceptions;

use MilesChou\Pherm\Concerns\CellsTrait;
use Tests\TestCase;

class CellsTraitTest extends TestCase
{
    /**
     * @var CellsTrait|\PHPUnit\Framework\MockObject\MockObject
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = $this->getMockForTrait(CellsTrait::class);
        $this->target->method('size')
            ->willReturn([10, 20]);
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldResetAllCell(): void
    {
        $this->target->resetCell();

        $this->assertCount(200, $this->target->getCells());
        $this->assertSame([], $this->target->getCell(0, 0));
        $this->assertSame([], $this->target->getCell(9, 19));
    }

    /**
     * @test
     */
    public function shouldWriteAndGetAtSameCell(): void
    {
        $this->target->resetCell();

        $this->target->writeCell(5, 5, 'ok', 50, 30);

        $this->assertSame(['ok', 50, 30], $this->target->getCell(5, 5));
    }
}
