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
     * @param int $column
     * @param int $row
     * @return Terminal
     */
    public function move(int $column, int $row): Terminal;

    /**
     * Move the cursor to the bottom left of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function bottom(int $column = 1): Terminal;

    /**
     * Move the cursor to the center of the window
     *
     * @param int $deltaColumn
     * @param int $deltaRow
     * @return Terminal
     */
    public function center(int $deltaColumn = 0, int $deltaRow = 0): Terminal;

    /**
     * Move the cursor to a specific column
     *
     * @param int $column
     * @return Terminal
     */
    public function column(int $column): Terminal;

    /**
     * @return array [x, y]
     */
    public function current(): array;

    /**
     * Move the cursor to the bottom right of the window
     *
     * @param int $backwardColumn
     * @param int $backwardRow
     * @return Terminal
     */
    public function end(int $backwardColumn = 0, int $backwardRow = 0): Terminal;

    /**
     * Move the cursor to the middle of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function middle(int $column = 1): Terminal;

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $row
     * @return Terminal
     */
    public function row(int $row): Terminal;

    /**
     * Move the cursor to the top left of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function top(int $column = 1): Terminal;

    /**
     * @param int $x
     * @param int $y
     * @return static
     */
    public function position(int $x, int $y);
}
