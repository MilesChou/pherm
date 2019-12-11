<?php

namespace MilesChou\Pherm;

trait TerminalAwareTrait
{
    /**
     * @var Terminal
     */
    protected $terminal;

    /**
     * @return Terminal
     */
    public function getTerminal(): Terminal
    {
        return $this->terminal;
    }

    /**
     * @param Terminal $terminal
     * @return static
     */
    public function setTerminal(Terminal $terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }
}
