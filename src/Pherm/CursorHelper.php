<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\PositionAwareTrait;

class CursorHelper
{
    use PositionAwareTrait;
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

    public function bottom(int $x = 1): Terminal
    {
        return $this->move($x, $this->control->tty->height());
    }

    public function center(int $deltaX = 0, int $deltaY = 0): Terminal
    {
        return $this->move(
            (int)($this->control->tty->width() / 2) + $deltaX,
            (int)($this->control->tty->height() / 2) + $deltaY
        );
    }

    public function column(int $x): Terminal
    {
        return $this->move($x, 1);
    }

    public function end(int $backwardX = 0, int $backwardY = 0): Terminal
    {
        return $this->move(
            $this->control->tty->width() - $backwardX,
            $this->control->tty->height() - $backwardY
        );
    }

    public function instant(int $x, int $y): Terminal
    {
        $this->control->checkPosition($x, $y);

        $this->terminal->getOutput()->write($this->control->cup($y, $x));

        return $this->terminal;
    }

    public function last(): array
    {
        return $this->getPosition();
    }

    public function middle(int $x = 1): Terminal
    {
        return $this->move($x, $this->control->tty->height() / 2);
    }

    public function move(int $x, int $y): Terminal
    {
        if ($this->terminal->isInstantOutput()) {
            $this->instant($x, $y);
        } else {
            $this->position($x, $y);
        }

        return $this->terminal;
    }

    public function position(int $x, int $y)
    {
        $this->control->checkPosition($x, $y);

        return $this->setPosition($x, $y);
    }

    public function row(int $y): Terminal
    {
        return $this->move(1, $y);
    }

    public function top(int $x = 1): Terminal
    {
        return $this->move($x, 1);
    }
}
