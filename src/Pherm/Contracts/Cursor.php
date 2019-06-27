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
    public function moveBottom(int $column = 1): Terminal;

    /**
     * Move the cursor to the center of the window
     *
     * @param int $deltaColumn
     * @param int $deltaRow
     * @return Terminal
     */
    public function moveCenter(int $deltaColumn = 0, int $deltaRow = 0): Terminal;

    /**
     * Move the cursor to a specific column
     *
     * @param int $column
     * @return Terminal
     */
    public function moveColumn(int $column): Terminal;

    /**
     * Move the cursor to the bottom right of the window
     *
     * @param int $backwardColumn
     * @param int $backwardRow
     * @return Terminal
     */
    public function moveEnd(int $backwardColumn = 0, int $backwardRow = 0): Terminal;

    /**
     * Move the cursor to the middle of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function moveMiddle(int $column = 1): Terminal;

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $row
     * @return Terminal
     */
    public function moveRow(int $row): Terminal;

    /**
     * Move the cursor to the top left of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function moveTop(int $column = 1): Terminal;

    /**
     * @param int $x
     * @param int $y
     * @return static
     */
    public function position(int $x, int $y);
}
