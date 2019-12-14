<?php

namespace MilesChou\Pherm\Input;

interface InputInterface
{
    /**
     * Whether the stream is connected to an interactive terminal
     *
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Callback should be called with the number of bytes requested
     * when ready.
     *
     * @param int $numBytes
     * @param callable $callback
     */
    public function read(int $numBytes, callable $callback): void;
}
