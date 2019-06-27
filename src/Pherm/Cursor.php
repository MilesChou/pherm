<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\Terminal;

/**
 * @method Terminal bottom(int $column = 0)
 * @method Terminal center(int $deltaColumn = 0, int $deltaRow = 0)
 * @method Terminal column(int $column)
 * @method Terminal end(int $backwardColumn = 0, int $backwardRow = 0)
 * @method Terminal middle(int $column = 0)
 * @method Terminal row(int $row)
 * @method Terminal top(int $column = 0)
 */
class Cursor implements CursorContract
{
    use TerminalAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @param Terminal $terminal
     * @param Control $control
     */
    public function __construct(Terminal $terminal, Control $control)
    {
        $this->setTerminal($terminal);
        $this->control = $control;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $method = 'move' . ucfirst($name))) {
            return $this->{$method}(...$arguments);
        }
    }

    public function move(int $column, int $row): Terminal
    {
        $this->terminal->write($this->control->cup($row, $column));

        return $this->terminal;
    }

    public function moveBottom(int $column = 0): Terminal
    {
        return $this->move($column, $this->terminal->height());
    }

    public function moveCenter(int $deltaColumn = 0, int $deltaRow = 0): Terminal
    {
        return $this->move(
            (int)($this->terminal->width() / 2) + $deltaColumn,
            (int)($this->terminal->height() / 2) + $deltaRow
        );
    }

    public function moveColumn(int $column): Terminal
    {
        return $this->move($column, 0);
    }

    public function moveEnd(int $backwardColumn = 0, int $backwardRow = 0): Terminal
    {
        return $this->move(
            $this->terminal->width() - $backwardColumn,
            $this->terminal->height() - $backwardRow
        );
    }

    public function moveMiddle(int $column = 0): Terminal
    {
        return $this->move($column, $this->terminal->height() / 2);
    }

    public function moveRow(int $row): Terminal
    {
        return $this->move(0, $row);
    }

    public function moveTop(int $column = 0): Terminal
    {
        return $this->move($column, 0);
    }
}
