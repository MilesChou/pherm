<?php

namespace MilesChou\Pherm\Contracts;

/**
 * Interface for move Cursor
 */
interface Cursor
{
    /**
     * Return the last position
     *
     * @return array
     */
    public function last(): array;

    /**
     * Move the cursor to specific position
     *
     * @param int $x
     * @param int $y
     * @return Terminal
     */
    public function move(int $x, int $y): Terminal;

    /**
     * Move the cursor to the bottom left of the window
     *
     * @param int $x
     * @return Terminal
     */
    public function bottom(int $x = 1): Terminal;

    /**
     * Move the cursor to the center of the window
     *
     * @param int $deltaX
     * @param int $deltaY
     * @return Terminal
     */
    public function center(int $deltaX = 0, int $deltaY = 0): Terminal;

    /**
     * Move the cursor to a specific column
     *
     * @param int $x
     * @return Terminal
     */
    public function column(int $x): Terminal;

    /**
     * @return array [x, y]
     */
    public function current(): array;

    /**
     * Move the cursor to the bottom right of the window
     *
     * @param int $backwardX
     * @param int $backwardY
     * @return Terminal
     */
    public function end(int $backwardX = 0, int $backwardY = 0): Terminal;

    /**
     * Move the cursor to the middle of the window
     *
     * @param int $x
     * @return Terminal
     */
    public function middle(int $x = 1): Terminal;

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $y
     * @return Terminal
     */
    public function row(int $y): Terminal;

    /**
     * Move the cursor to the top left of the window
     *
     * @param int $x
     * @return Terminal
     */
    public function top(int $x = 1): Terminal;

    /**
     * @param int $x
     * @param int $y
     * @return static
     */
    public function position(int $x, int $y);
}
