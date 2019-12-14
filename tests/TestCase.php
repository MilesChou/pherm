<?php

namespace Tests;

use Illuminate\Container\Container;
use MilesChou\Pherm\Control;
use MilesChou\Pherm\Input\InputInterface;
use MilesChou\Pherm\Input\StringInput;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Output\OutputInterface;
use MilesChou\Pherm\Terminal;
use MilesChou\Pherm\TTY;
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
     * @return Mockery\MockInterface|TTY
     */
    protected function createTTYMock()
    {
        $mock = Mockery::mock(TTY::class);
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
        $container = new Container();

        $container->instance(InputInterface::class, new StringInput());
        $container->instance(OutputInterface::class, new BufferedOutput());
        $container->instance(TTY::class, $this->createTTYMock());

        $instance = new Terminal($container);
        $instance->enableInstantOutput();

        return $instance;
    }
}
