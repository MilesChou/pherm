<?php

namespace MilesChou\Pherm\Contracts;

interface Terminal
{
    /**
     * @return int
     */
    public function width(): int;

    /**
     * @return int
     */
    public function height(): int;

    /**
     * Get the number of colours the terminal supports (1, 8, 256, true colours)
     */
    public function getColourSupport(): int;

    /**
     * Disables echoing every character back to the terminal. This means
     * we do not have to clear the line when reading.
     */
    public function disableEchoBack();

    /**
     * Enable echoing back every character input to the terminal.
     */
    public function enableEchoBack();

    /**
     * Is echo back mode enabled
     */
    public function isEchoBack(): bool;

    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode();

    /**
     * Enable canonical input - read input by line
     *
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function enableCanonicalMode();

    /**
     * Is canonical mode enabled or not
     */
    public function isCanonicalMode(): bool;

    /**
     * Check if the Input & Output streams are interactive. Eg - they are
     * connected to a terminal.
     *
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Test whether terminal supports colour output
     */
    public function supportsColour(): bool;

    /**
     * Clear the terminal window
     */
    public function clear();

    /**
     * Clear the current cursors line
     */
    public function clearLine();

    /**
     * Erase screen from the current line down to the bottom of the screen
     */
    public function clearDown();

    /**
     * Clean the whole console without jumping the window
     */
    public function clean();

    /**
     * Enable cursor display
     */
    public function enableCursor();

    /**
     * Disable cursor display
     */
    public function disableCursor();

    /**
     * Read from the input stream
     *
     * @param int $bytes
     * @return string
     */
    public function read(int $bytes): string;

    /**
     * Write to the output stream
     *
     * @param string $buffer
     */
    public function write(string $buffer);

    /**
     * Write to the output stream on specific cursor
     *
     * @param int $row
     * @param int $column
     * @param string $buffer
     */
    public function writeCursor(int $row, int $column, string $buffer);
}
