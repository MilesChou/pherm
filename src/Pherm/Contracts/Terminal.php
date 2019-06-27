<?php

namespace MilesChou\Pherm\Contracts;

interface Terminal
{
    /**
     * @param int|null $foreground
     * @param int|null $background
     * @return static
     * @see https://en.wikipedia.org/wiki/ANSI_escape_code#8-bit
     */
    public function attribute(?int $foreground = null, ?int $background = null);

    /**
     * @param int $defaultBackground
     * @return static
     * @see https://en.wikipedia.org/wiki/ANSI_escape_code#8-bit
     */
    public function defaultBackground(int $defaultBackground);

    /**
     * @param int $defaultForeground
     * @return static
     * @see https://en.wikipedia.org/wiki/ANSI_escape_code#8-bit
     */
    public function defaultForeground(int $defaultForeground);

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
     *
     * @return static
     */
    public function disableEchoBack();

    /**
     * Enable echoing back every character input to the terminal.
     *
     * @return static
     */
    public function enableEchoBack();

    /**
     * Is echo back mode enabled
     */
    public function isEchoBack(): bool;

    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode();

    /**
     * Enable canonical input - read input by line
     *
     * @return static
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
     * @return bool
     */
    public function isInstantOutput(): bool;

    /**
     * Clear the terminal window
     *
     * @return static
     */
    public function clear();

    /**
     * Clear the current cursors line
     *
     * @return static
     */
    public function clearLine();

    /**
     * Erase screen from the current line down to the bottom of the screen
     *
     * @return static
     */
    public function clearDown();

    /**
     * Enable cursor display
     *
     * @return static
     */
    public function enableCursor();

    /**
     * Disable cursor display
     *
     * @return static
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
     * @param string $chars
     * @param int|null $fg
     * @param int|null $bg
     * @return static
     */
    public function write(string $chars, ?int $fg = null, ?int $bg = null);

    /**
     * Write to the output stream on specific cursor
     *
     * @param int $x
     * @param int $y
     * @param string $char
     * @return static
     */
    public function writeCursor(int $x, int $y, string $char);

    /**
     * @return OutputStream
     */
    public function getOutput(): OutputStream;

    /**
     * @return array [x, y]
     */
    public function size(): array;
}
