<?php

use Illuminate\Container\Container;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new Container()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();
$terminal->cursor()->top();

$str = 'Hello 世界!';

$payload = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

foreach (range(16, 231) as $i => $bg) {
    $terminal->attribute(((int)$i % 36 < 18 ? 15 : 0), $bg)->write($payload[$i % 36]);

    if ($i % 36 === 35) {
        $terminal->write("\n");
    }
}

$terminal->cursor()->center(-(mb_strlen($str) / 2))->write($str);

$terminal->flush();
