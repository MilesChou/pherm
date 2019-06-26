<?php

namespace MilesChou\Pherm\Contracts;

/**
 * Interface for move Cursor
 */
interface Cursor
{
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
    public function moveBottom(int $column = 0): Terminal;

    /**
     * Move the cursor to the center of the window
     *
     * @param int $columnDelta
     * @return Terminal
     */
    public function moveCenter(int $columnDelta = 0): Terminal;

    /**
     * Move the cursor to a specific column
     *
     * @param int $column
     * @return Terminal
     */
    public function moveColumn(int $column): Terminal;

    /**
     * Move the cursor to the bottom right of the window
     */
    public function moveEnd(): Terminal;

    /**
     * Move the cursor to the middle of the window
     *
     * @param int $column
     * @return Terminal
     */
    public function moveMiddle(int $column = 0): Terminal;

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
    public function moveTop(int $column = 0): Terminal;
}
