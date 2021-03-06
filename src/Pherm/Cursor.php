<?php

namespace MilesChou\Pherm;

class Cursor
{
    /**
     * @var Control
     */
    private $control;

    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var TTY
     */
    private $tty;

    /**
     * @param Terminal $terminal
     * @param Control $control
     */
    public function __construct(Terminal $terminal, Control $control)
    {
        $this->control = $control;
        $this->terminal = $terminal;
        $this->tty = $control->tty();
    }

    public function bottom(int $x = 1): Terminal
    {
        return $this->move($x, $this->tty->height());
    }

    public function center(int $deltaX = 0, int $deltaY = 0): Terminal
    {
        return $this->move(
            (int)($this->tty->width() / 2) + $deltaX,
            (int)($this->tty->height() / 2) + $deltaY
        );
    }

    public function column(int $x): Terminal
    {
        return $this->move($x, 1);
    }

    public function end(int $backwardX = 0, int $backwardY = 0): Terminal
    {
        return $this->move(
            $this->tty->width() - $backwardX,
            $this->tty->height() - $backwardY
        );
    }

    public function instant(int $x, int $y): Terminal
    {
        $this->position($x, $y);

        $this->terminal->getOutput()->write($this->control->cup($y, $x));

        return $this->terminal;
    }

    /**
     * @return array<int>
     */
    public function last(): array
    {
        return $this->terminal->getPosition();
    }

    public function middle(int $x = 1): Terminal
    {
        return $this->move($x, $this->tty->height() / 2);
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

    public function position(int $x, int $y): Cursor
    {
        $this->control->checkPosition($x, $y);
        $this->terminal->setPosition($x, $y);

        return $this;
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
