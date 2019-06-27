<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$keyboard = [
    'K_ESC' => [[1, 1, 'E'], [2, 1, 'S'], [3, 1, 'C']],
    'K_F1' => [[6, 1, 'F'], [7, 1, '1']],
    'K_F2' => [[9, 1, 'F'], [10, 1, '2']],
    'K_F3' => [[12, 1, 'F'], [13, 1, '3']],
    'K_F4' => [[15, 1, 'F'], [16, 1, '4']],
    'K_F5' => [[19, 1, 'F'], [20, 1, '5']],
    'K_F6' => [[22, 1, 'F'], [23, 1, '6']],
    'K_F7' => [[25, 1, 'F'], [26, 1, '7']],
    'K_F8' => [[28, 1, 'F'], [29, 1, '8']],
    'K_F9' => [[33, 1, 'F'], [34, 1, '9']],
    'K_F10' => [[36, 1, 'F'], [37, 1, '1'], [38, 1, '0']],
    'K_F11' => [[40, 1, 'F'], [41, 1, '1'], [42, 1, '1']],
    'K_F12' => [[44, 1, 'F'], [45, 1, '1'], [46, 1, '2']],
    'K_TILDE' => [[1, 4, '`']],
    'K_1' => [[4, 4, '1']],
    'K_2' => [[7, 4, '2']],
    'K_3' => [[10, 4, '3']],
    'K_4' => [[13, 4, '4']],
    'K_5' => [[16, 4, '5']],
    'K_6' => [[19, 4, '6']],
    'K_7' => [[22, 4, '7']],
    'K_8' => [[25, 4, '8']],
    'K_9' => [[28, 4, '9']],
    'K_0' => [[31, 4, '0']],
    'K_MINUS' => [[34, 4, '-']],
    'K_EQUALS' => [[37, 4, '=']],
    'K_BACKSLASH' => [[40, 4, '\\']],
    'K_TAB' => [[1, 6, 'T'], [2, 6, 'A'], [3, 6, 'B']],
    'K_Q' => [[6, 6, 'Q']],
    'K_W' => [[9, 6, 'W']],
    'K_E' => [[12, 6, 'E']],
    'K_R' => [[15, 6, 'R']],
    'K_T' => [[18, 6, 'T']],
    'K_Y' => [[21, 6, 'Y']],
    'K_U' => [[24, 6, 'U']],
    'K_I' => [[27, 6, 'I']],
    'K_O' => [[30, 6, 'O']],
    'K_P' => [[33, 6, 'P']],
    'K_LSQB' => [[36, 6, '[']],
    'K_LCUB' => [[36, 6, '{']],
    'K_RSQB' => [[39, 6, ']']],
    'K_RCUB' => [[39, 6, '}']],
    'K_CAPS' => [[1, 8, 'C'], [2, 8, 'A'], [3, 8, 'P'], [4, 8, 'S']],
    'K_A' => [[7, 8, 'A']],
    'K_S' => [[10, 8, 'S']],
    'K_D' => [[13, 8, 'D']],
    'K_F' => [[16, 8, 'F']],
    'K_G' => [[19, 8, 'G']],
    'K_H' => [[22, 8, 'H']],
    'K_J' => [[25, 8, 'J']],
    'K_K' => [[28, 8, 'K']],
    'K_L' => [[31, 8, 'L']],
    'K_SEMICOLON' => [[34, 8, ';']],
    'K_QUOTE' => [[37, 8, '\'']],
    'K_LSHIFT' => [[1, 10, 'S'], [2, 10, 'H'], [3, 10, 'I'], [4, 10, 'F'], [5, 10, 'T']],
    'K_Z' => [[9, 10, 'Z']],
    'K_X' => [[12, 10, 'X']],
    'K_C' => [[15, 10, 'C']],
    'K_V' => [[18, 10, 'V']],
    'K_B' => [[21, 10, 'B']],
    'K_N' => [[24, 10, 'N']],
    'K_M' => [[27, 10, 'M']],
    'K_COMMA' => [[30, 10, ',']],
    'K_PERIOD' => [[33, 10, '.']],
    'K_SLASH' => [[36, 10, '/']],
    'K_RSHIFT' => [[42, 10, 'S'], [43, 10, 'H'], [44, 10, 'I'], [45, 10, 'F'], [46, 10, 'T']],
];

$terminal = new Terminal(new InputStream(), new OutputStream());
$terminal->bootstrap();
$terminal->enableInstantOutput();
$terminal->disableCanonicalMode();
$terminal->disableEchoBack();
$terminal->disableCursor();
$terminal->clear();

$terminal->attribute(15, 32);

foreach ($keyboard as $key => $data) {
    foreach ($data as $item) {
        $terminal->writeCursor($item[0], $item[1], $item[2]);
    }
}

$terminal->move()->bottom();

while (true) {
    $input = $terminal->read(4);

    if ($input === 'q') {
        break;
    }
}
