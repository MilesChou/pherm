<?php

namespace Tests\Pherm\Input;

use MilesChou\Pherm\Input\InputStream;
use Tests\TestCase;

class InputStreamTest extends TestCase
{
    public function testNonStream(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid stream');

        new InputStream(42);
    }

    public function testNotReadable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a readable stream');

        new InputStream(\STDOUT);
    }

    public function testRead(): void
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, '1234');
        rewind($stream);

        $inputStream = new InputStream($stream);

        $input = '';
        $inputStream->read(4, function ($buffer) use (&$input) {
            $input .= $buffer;
        });

        $this->assertSame('1234', $input);

        fclose($stream);
    }
}
