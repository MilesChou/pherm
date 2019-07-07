<?php

namespace MilesChou\Pherm\Contracts;

use MilesChou\Pherm\CursorHelper;

interface Terminal
{
    /**
     * @return static
     */
    public function bootstrap(): Terminal;

    /**
     * Clear the terminal window
     *
     * @return static
     */
    public function clear();

    /**
     * Erase screen from the current line down to the bottom of the screen
     *
     * @return static
     */
    public function clearDown();

    /**
     * Clear the current cursors line
     *
     * @return static
     */
    public function clearLine();

    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode();

    /**
     * Disable cursor display
     *
     * @return static
     */
    public function disableCursor();

    /**
     * Disables echoing every character back to the terminal. This means
     * we do not have to clear the line when reading.
     *
     * @return static
     */
    public function disableEchoBack();

    /**
     * Enable canonical input - read input by line
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function enableCanonicalMode();

    /**
     * Enable cursor display
     *
     * @return static
     */
    public function enableCursor();

    /**
     * Enable echoing back every character input to the terminal.
     *
     * @return static
     */
    public function enableEchoBack();

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute;

    /**
     * @return CursorHelper
     */
    public function getCursor(): CursorHelper;

    /**
     * @return InputStream
     */
    public function getInput(): InputStream;

    /**
     * @return OutputStream
     */
    public function getOutput(): OutputStream;

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
     * @return bool
     */
    public function isInstantOutput(): bool;

    /**
     * Check if the Input & Output streams are interactive. Eg - they are
     * connected to a terminal.
     *
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Read from the input stream
     *
     * @param int $bytes
     * @return string
     */
    public function read(int $bytes): string;

    /**
     * @return array [x, y]
     */
    public function size(): array;

    /**
     * @return int
     */
    public function width(): int;

    /**
     * Write to the output stream
     *
     * @param string $buffer
     * @return static
     */
    public function write(string $buffer);
}
