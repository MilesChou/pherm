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
     * @param TerminalContract $terminal
     */
    public function setTerminal(TerminalContract $terminal): void
    {
        $this->terminal = $terminal;
    }
}
