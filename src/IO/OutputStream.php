<?php

namespace MilesChou\Pherm\IO;

interface OutputStream
{
    /**
     * Whether the stream is connected to an interactive terminal
     *
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Write the buffer to the stream
     *
     * @param string $buffer
     */
    public function write(string $buffer): void;
}
