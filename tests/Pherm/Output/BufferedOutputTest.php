<?php

namespace Tests\Pherm\Output;

use MilesChou\Pherm\Output\BufferedOutput;
use Tests\TestCase;

class BufferedOutputTest extends TestCase
{
    public function testFetch() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        $this->assertSame('one', $output->fetch());
    }

    public function testFetchWithMultipleWrites() : void
    {
        $output = new BufferedOutput;
        $output->write('one');
        $output->write('two');

        $this->assertSame('onetwo', $output->fetch());
    }

    public function testFetchCleansBufferByDefault() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        $this->assertSame('one', $output->fetch());
        $this->assertSame('', $output->fetch());
    }

    public function testFetchWithoutCleaning() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        $this->assertSame('one', $output->fetch(false));

        $output->write('two');

        $this->assertSame('onetwo', $output->fetch(false));
    }

    public function testToString() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        $this->assertSame('one', (string) $output);
    }
}
