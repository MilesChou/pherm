<?php

namespace Tests;

use MilesChou\Pherm\App;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Control;
use MilesChou\Pherm\Input\StringInput;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\TTY;
use MilesChou\Pherm\Terminal;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @return Mockery\MockInterface|Control
     */
    protected function createControlMock()
    {
        $mock = Mockery::mock(Control::class);
        $mock->makePartial();

        $mock->shouldReceive('width')->andReturn(80);
        $mock->shouldReceive('height')->andReturn(24);

        return $mock;
    }

    /**
     * @return Terminal
     */
    protected function createTerminalInstance(): Terminal
    {
        $app = App::create();
        $app->instance(InputStream::class, new StringInput);
        $app->instance(OutputStream::class, new BufferedOutput);

        /** @var Terminal $instance */
        $instance = $app->createTerminal();
        $instance->setControl($this->createControlMock());
        $instance->enableInstantOutput();

        return $instance;
    }
}
