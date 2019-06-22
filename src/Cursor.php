<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\Terminal;

/**
 * @method Terminal bottom(int $column = 0)
 * @method Terminal center(int $columnDelta = 0)
 * @method Terminal column(int $column)
 * @method Terminal end()
 * @method Terminal middle(int $column = 0)
 * @method Terminal row(int $row)
 * @method Terminal top(int $column = 0)
 */
class Cursor implements CursorContract
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @param Terminal $terminal
     */
    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $method = 'move' . ucfirst($name))) {
            return $this->{$method}(...$arguments);
        }
    }

    public function move(int $row, int $column): Terminal
    {
        $this->terminal->write(sprintf("\033[%d;%dH", $row, $column));

        return $this->terminal;
    }

    public function moveBottom(int $column = 0): Terminal
    {
        return $this->move($this->terminal->height(), $column);
    }

    public function moveCenter(int $columnDelta = 0): Terminal
    {
        return $this->move($this->terminal->height() / 2, $this->terminal->width() / 2 + $columnDelta);
    }

    public function moveColumn(int $column): Terminal
    {
        return $this->move(0, $column);
    }

    public function moveEnd(): Terminal
    {
        return $this->move($this->terminal->height(), $this->terminal->width());
    }

    public function moveMiddle(int $column = 0): Terminal
    {
        return $this->move($this->terminal->height() / 2, $column);
    }

    public function moveRow(int $row): Terminal
    {
        return $this->move($row, 0);
    }

    public function moveTop(int $column = 0): Terminal
    {
        return $this->move(0, $column);
    }
}
