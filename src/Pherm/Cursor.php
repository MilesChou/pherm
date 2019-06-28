<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Contracts\Cursor as CursorContract;
use MilesChou\Pherm\Contracts\Terminal;
use OverflowException;

class Cursor implements CursorContract
{
    use TerminalAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @var int
     */
    private $x = 1;

    /**
     * @var int
     */
    private $y = 1;

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

    public function bottom(int $column = 1): Terminal
    {
        return $this->move($column, $this->terminal->height());
    }

    public function center(int $deltaColumn = 0, int $deltaRow = 0): Terminal
    {
        return $this->move(
            (int)($this->terminal->width() / 2) + $deltaColumn,
            (int)($this->terminal->height() / 2) + $deltaRow
        );
    }

    public function column(int $column): Terminal
    {
        return $this->move($column, 1);
    }

    public function current(): array
    {
        // store state
        $icanon = $this->terminal->isCanonicalMode();
        $echo = $this->terminal->isEchoBack();

        if ($icanon) {
            $this->terminal->disableCanonicalMode();
        }

        if ($echo) {
            $this->terminal->disableEchoBack();
        }

        fwrite(STDOUT, $this->control->dsr);

        // 16 is work when return "\033[xxx;xxxH"
        if (!$cpr = fread(STDIN, 16)) {
            return [-1, -1];
        }

        // restore state
        if ($icanon) {
            $this->terminal->enableCanonicalMode();
        }

        if ($echo) {
            $this->terminal->enableEchoBack();
        }

        if (sscanf(trim($cpr), $this->control->cpr, $row, $col) === 2) {
            return [$col, $row];
        }

        return [-1, -1];
    }

    public function end(int $backwardColumn = 0, int $backwardRow = 0): Terminal
    {
        return $this->move(
            $this->terminal->width() - $backwardColumn,
            $this->terminal->height() - $backwardRow
        );
    }

    public function last(): array
    {
        return [$this->x, $this->y];
    }

    public function middle(int $column = 1): Terminal
    {
        return $this->move($column, $this->terminal->height() / 2);
    }

    public function move(int $column, int $row): Terminal
    {
        $this->position($column, $row);

        if ($this->terminal->isInstantOutput()) {
            $this->terminal->getOutput()->write($this->control->cup($row, $column));
        }

        return $this->terminal;
    }

    public function position(int $x, int $y)
    {
        [$sizeX, $sizeY] = $this->terminal->size();

        if ($x < 1 || $x > $sizeX) {
            throw new OverflowException("X expect in range 1 to $sizeX, actual is '$x'");
        }

        if ($y < 1 || $y > $sizeY) {
            throw new OverflowException("Y expect in range 1 to $sizeY, actual is '$y'");
        }

        $this->x = $x;
        $this->y = $y;

        return $this;
    }

    public function row(int $row): Terminal
    {
        return $this->move(1, $row);
    }

    public function top(int $column = 1): Terminal
    {
        return $this->move($column, 1);
    }
}
