<?php

namespace Tests\Pherm\Input;

use MilesChou\Pherm\Input\StringInput;
use Tests\TestCase;

class StringInputTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeOkayWhenRead(): void
    {
        $target = new StringInput();
        $target->input('中文');

        $target->read(3, function ($buffer) {
            $this->assertSame('中', $buffer);
        });

        $target->input('世界');

        $target->read(3, function ($buffer) {
            $this->assertSame('文', $buffer);
        });

        $target->read(6, function ($buffer) {
            $this->assertSame('世界', $buffer);
        });
    }
}
