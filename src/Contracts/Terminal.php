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
    public function disableEchoBack(): void;

    /**
     * Enable echoing back every character input to the terminal.
     */
    public function enableEchoBack(): void;

    /**
     * Is echo back mode enabled
     */
    public function isEchoBack(): bool;

    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode(): void;

    /**
     * Enable canonical input - read input by line
     *
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function enableCanonicalMode(): void;

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
     * Restore the terminals original configuration
     */
    public function restoreOriginalConfiguration(): void;

    /**
     * Test whether terminal supports colour output
     */
    public function supportsColour(): bool;

    /**
     * Clear the terminal window
     */
    public function clear(): void;

    /**
     * Clear the current cursors line
     */
    public function clearLine(): void;

    /**
     * Erase screen from the current line down to the bottom of the screen
     */
    public function clearDown(): void;

    /**
     * Clean the whole console without jumping the window
     */
    public function clean(): void;

    /**
     * Enable cursor display
     */
    public function enableCursor(): void;

    /**
     * Disable cursor display
     */
    public function disableCursor(): void;

    /**
     * Move the cursor to the top left of the window
     */
    public function moveCursorToTop(): void;

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $row
     */
    public function moveCursorToRow(int $row): void;

    /**
     * Move the cursor to a specific column
     *
     * @param int $column
     */
    public function moveCursorToColumn(int $column): void;

    /**
     * Move the cursor to specific position
     *
     * @param int $column
     * @param int $row
     */
    public function moveCursor(int $column, int $row): void;

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
    public function write(string $buffer): void;

    /**
     * Write to the output stream on specific cursor
     *
     * @param int $column
     * @param int $row
     * @param string $buffer
     */
    public function writeCursor(int $column, int $row, string $buffer): void;
}
