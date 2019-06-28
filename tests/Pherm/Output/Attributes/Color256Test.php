<?php

namespace Tests\Pherm\Output;

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
        $this->target = new Color256(new BufferedOutput);
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @dataProvider validFgBgs
     * @test
     */
    public function shouldSendClearAttrStrWhenCallSend(): void
    {
        $this->target->send(1, 1);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertStringStartsWith("\033[m", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldSendClearAttrStrWhenCallSendWithDefaultColor(): void
    {
        $this->target->send(Color256::COLOR_DEFAULT, Color256::COLOR_DEFAULT);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertStringStartsWith("\033[m", $output->fetch());
    }

    /**
     * @test
     */
    public function shouldSendNothingWhenCallSendWithSameAttr(): void
    {
        $this->target->send(1, 1);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();
        $output->fetch();

        $this->target->send(1, 1);

        $this->assertTrue($output->isEmpty());
    }

    /**
     * @test
     */
    public function shouldSendCorrectFgBgAttrWhenCallSend(): void
    {
        $this->target->send(3, 4);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertContains("\033[38;5;3m", $output->fetch(false));
        $this->assertContains("\033[48;5;4m", $output->fetch(false));
    }

    /**
     * @test
     */
    public function shouldSendCorrectFgOnlyAttrWhenCallSendBgDefault(): void
    {
        $this->target->send(3, Color256::COLOR_DEFAULT);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertContains("\033[38;5;3m", $output->fetch(false));
        $this->assertNotContains("\033[48;5", $output->fetch(false));
    }

    /**
     * @test
     */
    public function shouldSendCorrectBgOnlyAttrWhenCallSendFgDefault(): void
    {
        $this->target->send(Color256::COLOR_DEFAULT, 3);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertNotContains("\033[38;5", $output->fetch(false));
        $this->assertContains("\033[48;5;3m", $output->fetch(false));
    }

    /**
     * @test
     */
    public function shouldSendBoldAttrWhenCallSendFgWithBold(): void
    {
        $this->target->send(3 | Color256::BOLD, 3);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertContains("\033[38;5;3m", $output->fetch(false));
        $this->assertContains("\033[48;5;3m", $output->fetch(false));
        $this->assertContains("\033[1m", $output->fetch(false));
        $this->assertNotContains("\033[5m", $output->fetch(false));
    }

    /**
     * @test
     */
    public function shouldSendBlinkAttrWhenCallSendBgWithBold(): void
    {
        $this->target->send(3, 3 | Color256::BOLD);

        /** @var BufferedOutput $output */
        $output = $this->target->getOutput();

        $this->assertContains("\033[38;5;3m", $output->fetch(false));
        $this->assertContains("\033[48;5;3m", $output->fetch(false));
        $this->assertContains("\033[5m", $output->fetch(false));
        $this->assertNotContains("\033[1m", $output->fetch(false));
    }
}
