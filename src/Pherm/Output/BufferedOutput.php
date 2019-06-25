<?php

namespace MilesChou\Pherm\Output;

use MilesChou\Pherm\Contracts\OutputStream;

class BufferedOutput implements OutputStream
{
    /**
     * @var string
     */
    private $buffer = '';

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

    public function isInteractive(): bool
    {
        return false;
    }

    public function write(string $buffer): void
    {
        $this->buffer .= $buffer;
    }
}
