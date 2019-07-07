<?php

namespace MilesChou\Pherm\Contracts;

/**
 * @see https://www.xfree86.org/current/ctlseqs.html
 */
interface Control
{
    /**
     * SGR parameters
     *
     * array key is mnemonic and value is character
     *
     * @see https://en.wikipedia.org/wiki/Control_character
     */
    public const CONTROL_CHARACTER = [
        'NUL' => "\x00",
        'ETX' => "\x03",
        'EOT' => "\x04",
        'BEL' => "\x07",
        'BS' => "\x08",
        'LF' => "\x0A",
        'CR' => "\x0D",
        'CAN' => "\x18",
        'ESC' => "\x1B",
    ];

    /**
     * CSI sequences
     *
     * array key is mnemonic and value is control sequences
     */
    public const CONTROL_SEQUENCES = [
        'CPR' => "\033[%d;%dR",
        'CUU' => "\033[A",
        'CUD' => "\033[B",
        'CUF' => "\033[C",
        'CUB' => "\033[D",
        'CUP' => "\033[%d;%dH",
        'DSR' => "\033[6n",
        'ED' => "\033[2J",
        'HVP' => "\033[%d;%df",
        'RCP' => "\033[u",
        'SCP' => "\033[S",
    ];
}
