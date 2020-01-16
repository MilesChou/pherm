<?php

namespace Tests\Pherm\Output\Attributes;

use MilesChou\Pherm\Output\Attributes\Color256;
use MilesChou\Pherm\Output\BufferedOutput;
use Tests\TestCase;

class Color256Test extends TestCase
{
    /**
     * @var Color256
     */
    private $target;

    public function validFgBgs()
    {
        return [
            [1, 1],
            [Color256::COLOR_DEFAULT, Color256::COLOR_DEFAULT],
        ];
    }

    protected function setUp(): void
    {
        $this->target = new Color256(new BufferedOutput());
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @dataProvider validFgBgs
     * @test
     */
    public function shouldSendClearAttrStrWhenCallGenerate($fg, $bg): void
    {
        $this->assertStringStartsWith("\033[m", $this->target->generate($fg, $bg));
    }

    /**
     * @test
     */
    public function shouldSendCorrectFgBgAttrWhenCallSend(): void
    {
        $actual = $this->target->generate(3, 4);

        $this->assertStringContainsString("\033[38;5;3m", $actual);
        $this->assertStringContainsString("\033[48;5;4m", $actual);
    }

    /**
     * @test
     */
    public function shouldSendCorrectFgOnlyAttrWhenCallSendBgDefault(): void
    {
        $actual = $this->target->generate(3, Color256::COLOR_DEFAULT);

        $this->assertStringContainsString("\033[38;5;3m", $actual);
        $this->assertStringNotContainsString("\033[48;5", $actual);
    }

    /**
     * @test
     */
    public function shouldSendCorrectBgOnlyAttrWhenCallSendFgDefault(): void
    {
        $actual = $this->target->generate(Color256::COLOR_DEFAULT, 3);

        $this->assertStringNotContainsString("\033[38;5", $actual);
        $this->assertStringContainsString("\033[48;5;3m", $actual);
    }

    /**
     * @test
     */
    public function shouldSendBoldAttrWhenCallSendFgWithBold(): void
    {
        $actual = $this->target->generate(3 | Color256::BOLD, 3);

        $this->assertStringContainsString("\033[38;5;3m", $actual);
        $this->assertStringContainsString("\033[48;5;3m", $actual);
        $this->assertStringContainsString("\033[1m", $actual);
        $this->assertStringNotContainsString("\033[5m", $actual);
    }

    /**
     * @test
     */
    public function shouldSendBlinkAttrWhenCallSendBgWithBold(): void
    {
        $actual = $this->target->generate(3, 3 | Color256::BOLD);

        $this->assertStringContainsString("\033[38;5;3m", $actual);
        $this->assertStringContainsString("\033[48;5;3m", $actual);
        $this->assertStringContainsString("\033[5m", $actual);
        $this->assertStringNotContainsString("\033[1m", $actual);
    }
}
