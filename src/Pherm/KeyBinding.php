<?php

namespace MilesChou\Pherm;

class KeyBinding
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var array
     */
    private $binding = [];

    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    public function set($key, callable $callback = null)
    {
        if (null === $callback) {
            $callback = function (Terminal $terminal, $key) {
                return $terminal->write($key);
            };
        }

        $this->binding[$key] = $callback;
    }

    public function setAll(array $all)
    {
        foreach ($all as $key => $callback) {
            $this->set($key, $callback);
        }
    }

    public function handle($input)
    {
        if (!array_key_exists($input, $this->binding)) {
            return;
        }

        $callback = $this->binding[$input];
        $callback($this->terminal, $input);
    }
}
