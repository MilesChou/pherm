<?php

namespace MilesChou\Pherm\Contracts;

interface TTY
{
    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode(): TTY;

    /**
     * Disables echoing every character back to the terminal. This means
     * we do not have to clear the line when reading.
     *
     * @return static
     */
    public function disableEchoBack(): TTY;

    /**
     * Enable canonical input - read input by line
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function enableCanonicalMode(): TTY;

    /**
     * Enable echoing back every character input to the terminal.
     *
     * @return static
     */
    public function enableEchoBack(): TTY;

    /**
     * @return int
     */
    public function height(): int;

    /**
     * Is canonical mode enabled or not
     */
    public function isCanonicalMode(): bool;

    /**
     * Is echo back mode enabled
     */
    public function isEchoBack(): bool;

    /**
     * @return int
     */
    public function width(): int;
}
