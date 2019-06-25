<?php

namespace Tests\Pherm\Output;

use MilesChou\Pherm\Output\OutputStream;
use Tests\TestCase;

class ResourceOutputStreamTest extends TestCase
{
    public function testNonStream() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid stream');
        new OutputStream(42);
    }

    public function testNotWritable() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a writable stream');
        new OutputStream(\STDIN);
    }

    public function testWrite() : void
    {
        $stream = fopen('php://memory', 'r+');
        $outputStream = new OutputStream($stream);
        $outputStream->write('123456789');

        rewind($stream);

        $this->assertSame('123456789', stream_get_contents($stream));
    }
}
