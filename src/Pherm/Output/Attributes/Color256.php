<?php

namespace MilesChou\Pherm\Output\Attributes;

use MilesChou\Pherm\Output\Attribute;

class Color256 extends Attribute
{
    protected function generateBoth(int $fg, int $bg): string
    {
        return $this->generateForeground($fg) . $this->genearateBackground($bg);
    }

    protected function generateForeground(int $fg): string
    {
        return "\033[38;5;{$fg}m";
    }

    protected function genearateBackground(int $bg): string
    {
        return "\033[48;5;{$bg}m";
    }
}
