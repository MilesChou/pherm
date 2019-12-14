<?php

namespace Tests\Pherm\Output;

use MilesChou\Pherm\Output\OutputStream;
use Tests\TestCase;

class OutputStreamTest extends TestCase
{
    public function testNonStream(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid stream');

        new OutputStream(42);
    }

    public function testNotWritable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a writable stream');

        new OutputStream(\STDIN);
    }
}
