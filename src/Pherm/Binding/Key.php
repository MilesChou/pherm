<?php

namespace MilesChou\Pherm\Binding;

use MilesChou\Pherm\Terminal;

class Key
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var array
     */
    private $binding = [];

    /**
     * @param Terminal $terminal
     */
    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    /**
     * @return Terminal
     */
    public function getTerminal(): Terminal
    {
        return $this->terminal;
    }

    /**
     * @param string $input
     */
    public function handle(string $input): void
    {
        if (!array_key_exists($input, $this->binding)) {
            return;
        }

        $callback = $this->binding[$input];
        $callback($this->terminal);
    }

    /**
     * @param string $key
     * @param callable|null $callback
     * @return static
     */
    public function set(string $key, callable $callback = null)
    {
        if (null === $callback) {
            $callback = function (Terminal $terminal) use ($key) {
                return $terminal->write($key);
            };
        }

        $this->binding[$key] = $callback;

        return $this;
    }

    /**
     * @param array $all
     * @return static
     */
    public function setAll(array $all)
    {
        foreach ($all as $key => $callback) {
            $this->set($key, $callback);
        }

        return $this;
    }
}
