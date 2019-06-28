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

    public function shouldGetContentInstantWhenEnableInstantOutput() : void
    {
        $stream = fopen('php://memory', 'rb+');
        $outputStream = new OutputStream($stream);
        $outputStream->enableInstantOutput();
        $outputStream->write('123456789');

        rewind($stream);

        $this->assertSame('123456789', stream_get_contents($stream));
    }

    /**
     * @test
     */
    public function shouldGetContentAfterFlushWhenWriteWithDisableInstantOutput() : void
    {
        $stream = fopen('php://memory', 'rb+');
        $outputStream = new OutputStream($stream);
        $outputStream->disableInstantOutput();
        $outputStream->write('123456789');

        rewind($stream);

        $this->assertSame('', stream_get_contents($stream));

        $outputStream->write('abc');
        $outputStream->flush();

        rewind($stream);

        $this->assertSame('123456789abc', stream_get_contents($stream));
    }
}
