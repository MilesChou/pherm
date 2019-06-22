<?php

namespace MilesChou\Pherm\IO;

interface InputStream
{
    /**
     * Callback should be called with the number of bytes requested
     * when ready.
     */
    public function read(int $numBytes, callable $callback) : void;

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool;
}
