<?php

namespace MilesChou\Pherm\Contracts;

interface Output
{
    /**
     * Flush the buffer to output stream
     */
    public function flush(): void;

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
