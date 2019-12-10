<?php

namespace MilesChou\Pherm;

use Illuminate\Container\Container;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;

class App extends Container
{
    /**
     * @return App
     */
    public static function create(): App
    {
        return new static();
    }

    public function __construct()
    {
        $this->registerBaseBindings();
    }

    /**
     * @return Terminal
     */
    public function createTerminal(): Contracts\Terminal
    {
        $terminal = $this->make(Contracts\Terminal::class);

        $this->instance(Terminal::class, $terminal);

        return $terminal;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance(Container::class, $this);

        $this->singleton(Contracts\Terminal::class, Terminal::class);
        $this->bind(Contracts\InputStream::class, InputStream::class);
        $this->bind(Contracts\OutputStream::class, OutputStream::class);
        $this->bind(Contracts\TTY::class, TTY::class);
    }
}
