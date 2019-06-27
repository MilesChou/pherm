<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Contracts\Terminal as TerminalContract;

trait TerminalAwareTrait
{
    /**
     * @var TerminalContract
     */
    protected $terminal;

    /**
     * @return TerminalContract
     */
    public function getTerminal(): TerminalContract
    {
        return $this->terminal;
    }

    /**
     * @param TerminalContract $terminal
     * @return static
     */
    public function setTerminal(TerminalContract $terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }
}
