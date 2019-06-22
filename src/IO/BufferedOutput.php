<?php

namespace MilesChou\Pherm\IO;

class BufferedOutput implements OutputStream
{
    private $buffer = '';

    public function write(string $buffer): void
    {
        $this->buffer .= $buffer;
    }

    public function fetch(bool $clean = true) : string
    {
        $buffer = $this->buffer;

        if ($clean) {
            $this->buffer = '';
        }

        return $buffer;
    }

    public function __toString() : string
    {
        return $this->fetch();
    }

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool
    {
        return false;
    }
}
