<?php

namespace MilesChou\Pherm\Output\Attributes;

use MilesChou\Pherm\Output\Attribute;

class Color256 extends Attribute
{
    protected function write(int $fg, int $bg): void
    {
        $this->output->write("\033[38;5;{$fg}m");
        $this->output->write("\033[48;5;{$bg}m");
    }

    protected function writeForeground(int $fg): void
    {
        $this->output->write("\033[38;5;{$fg}m");
    }

    protected function writeBackground(int $bg): void
    {
        $this->output->write("\033[48;5;{$bg}m");
    }
}
