<?php

namespace MilesChou\Pherm\IO;

interface OutputStream
{
    /**
     * Write the buffer to the stream
     */
    public function write(string $buffer) : void;

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool;
}
