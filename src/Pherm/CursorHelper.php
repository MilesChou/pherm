<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\ConfigTrait;
use MilesChou\Pherm\Concerns\PositionAwareTrait;
use MilesChou\Pherm\Contracts\Terminal;
use OverflowException;

class CursorHelper
{
    use ConfigTrait;
    use PositionAwareTrait;
    use TerminalAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @param Terminal $terminal
     * @param TTY $tty
     * @param Control $control
     */
    public function __construct(Terminal $terminal, TTY $tty, Control $control)
    {
        $this->setTerminal($terminal);
        $this->control = $control;
        $this->tty = $tty;

        $this->prepareConfiguration();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $method = 'move' . ucfirst($name))) {
            return $this->{$method}(...$arguments);
        }
    }

    public function bottom(int $x = 1): Terminal
    {
        return $this->move($x, $this->height);
    }

    public function center(int $deltaX = 0, int $deltaY = 0): Terminal
    {
        return $this->move(
            (int)($this->width / 2) + $deltaX,
            (int)($this->height / 2) + $deltaY
        );
    }

    public function column(int $x): Terminal
    {
        return $this->move($x, 1);
    }

    public function current(): array
    {
        // store state
        $icanon = $this->tty->isCanonicalMode();
        $echo = $this->tty->isEchoBack();

        if ($icanon) {
            $this->tty->disableCanonicalMode();
        }

        if ($echo) {
            $this->tty->disableEchoBack();
        }

        fwrite(STDOUT, $this->control->dsr);

        // 16 is work when return "\033[xxx;xxxH"
        if (!$cpr = fread(STDIN, 16)) {
            return [-1, -1];
        }

        // restore state
        if ($icanon) {
            $this->tty->enableCanonicalMode();
        }

        if ($echo) {
            $this->tty->enableEchoBack();
        }

        if (sscanf(trim($cpr), $this->control->cpr, $row, $col) === 2) {
            return [$col, $row];
        }

        return [-1, -1];
    }

    public function end(int $backwardX = 0, int $backwardY = 0): Terminal
    {
        return $this->move(
            $this->width - $backwardX,
            $this->height - $backwardY
        );
    }

    public function instant(int $x, int $y): Terminal
    {
        $this->checkPosition($x, $y);

        $this->terminal->getOutput()->write($this->control->cup($y, $x));

        return $this->terminal;
    }

    public function last(): array
    {
        return $this->getPosition();
    }

    public function middle(int $x = 1): Terminal
    {
        return $this->move($x, $this->height / 2);
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
        $this->checkPosition($x, $y);

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

    /**
     * @param int $x
     * @param int $y
     */
    private function checkPosition(int $x, int $y): void
    {
        if ($x < 1 || $x > $this->width) {
            throw new OverflowException("X expect in range 1 to {$this->width}, actual is '$x'");
        }

        if ($y < 1 || $y > $this->height) {
            throw new OverflowException("Y expect in range 1 to {$this->height}, actual is '$y'");
        }
    }
}
