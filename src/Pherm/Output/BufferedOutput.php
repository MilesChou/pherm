<?php

namespace MilesChou\Pherm\Output;

use MilesChou\Pherm\Contracts\OutputStream;

class BufferedOutput implements OutputStream
{
    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var bool
     */
    private $interactive;

    public function __toString(): string
    {
        return $this->fetch();
    }

    /**
     * @param bool $clean
     * @return string
     */
    public function fetch(bool $clean = true): string
    {
        $buffer = $this->buffer;

        if ($clean) {
            $this->buffer = '';
        }

        return $buffer;
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

    public function write(string $buffer): void
    {
        $this->buffer .= $buffer;
    }
}
