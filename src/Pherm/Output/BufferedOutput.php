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
            $this->flush();
        }

        return $buffer;
    }

    public function flush(): void
    {
        $this->buffer = '';
    }

    public function isEmpty(): bool
    {
        return '' === $this->buffer;
    }

    public function isInteractive(): bool
    {
        return $this->interactive;
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

    public function toByteArray()
    {
        return array_map('ord', str_split($this->buffer));
    }

    public function write(string $buffer): void
    {
        $this->buffer .= $buffer;
    }
}
