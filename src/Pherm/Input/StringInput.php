<?php

namespace MilesChou\Pherm\Input;

class StringInput implements InputInterface
{
    /**
     * @var array<string>
     */
    private $input = [];

    /**
     * @var bool
     */
    private $interactive = false;

    /**
     * @param string|array<string> $input
     * @return static
     */
    public function input($input)
    {
        if (!is_array($input)) {
            $input = str_split($input);
        }

        $this->input = array_merge($this->input, $input);

        return $this;
    }

    /**
     * @param bool $value
     * @return static
     */
    public function mockInteractive(bool $value)
    {
        $this->interactive = $value;

        return $this;
    }

    public function isInteractive(): bool
    {
        return $this->interactive;
    }

    public function read(int $numBytes, callable $callback): void
    {
        $buffer = [];

        for ($i = 0; $i < $numBytes; $i++) {
            $buffer[] = array_shift($this->input);
        }

        $callback(implode('', $buffer));
    }
}
