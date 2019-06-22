<?php

namespace Tests;

use MilesChou\Pherm\Contracts\InputStream as InputStreamContract;
use MilesChou\Pherm\Output\BufferedOutput;
use MilesChou\Pherm\Stty;
use MilesChou\Pherm\Terminal;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @param array $parseAllMock
     * @return Mockery\MockInterface|Stty
     */
    protected function createSttyMock($parseAllMock = [])
    {
        $stub = array_merge([
            'columns' => 80,
            'echo' => true,
            'icanon' => true,
            'rows' => 24,
        ], $parseAllMock);

        $mock = Mockery::mock(Stty::class);
        $mock->makePartial();
        $mock->shouldReceive('parseAll')->andReturn($stub);

        return $mock;
    }

    /**
     * @param array $parseAllMock
     * @return Terminal
     */
    protected function createTerminalInstance($parseAllMock = []): Terminal
    {
        $instance = new Terminal($this->createMock(InputStreamContract::class), new BufferedOutput());
        $instance->setStty($this->createSttyMock($parseAllMock));

        return $instance;
    }
}
