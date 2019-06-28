<?php

namespace Tests\Pherm\Support;

use MilesChou\Pherm\Support\Char;
use Tests\TestCase;

class CharTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn2WhenTheDoubleWidth(): void
    {
        $this->assertSame(2, Char::width('中'));
        $this->assertSame(2, Char::width('あ'));
        $this->assertSame(2, Char::width('가'));
        $this->assertSame(2, Char::width('😋'));
    }
}
