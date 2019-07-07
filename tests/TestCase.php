<?php

namespace Tests;

use MilesChou\Pherm\App;
use MilesChou\Pherm\Contracts\InputStream;
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
     * @param array $parseAllMock
     * @return Mockery\MockInterface|TTY
     */
    protected function createTTYMock($parseAllMock = [])
    {
        $stub = array_merge([
            'echo' => true,
            'icanon' => true,
        ], $parseAllMock);

        $mock = Mockery::mock(TTY::class);
        $mock->makePartial();
        $mock->shouldReceive('parseAll')->andReturn($stub);
        $mock->shouldReceive('width')->andReturn(80);
        $mock->shouldReceive('height')->andReturn(24);

        return $mock;
    }

    /**
     * @param array $parseAllMock
     * @return Terminal
     */
    protected function createTerminalInstance($parseAllMock = []): Terminal
    {
        $app = App::create();
        $app->instance(InputStream::class, new StringInput);
        $app->instance(OutputStream::class, new BufferedOutput);

        /** @var Terminal $instance */
        $instance = $app->createTerminal();
        $instance->setTTY($this->createTTYMock($parseAllMock));
        $instance->enableInstantOutput();

        return $instance;
    }
}
