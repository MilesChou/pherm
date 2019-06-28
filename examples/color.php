<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->bootstrap();

$terminal->clear();

$str = 'Hello world!';

$terminal->moveCursor()->top();

$payload = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

foreach (range(16, 231) as $i => $bg) {
    $terminal->attribute(((int)$i % 36 < 18 ? 15 : 0), $bg)->write($payload[$i % 36]);

    if ($i % 36 === 35) {
        $terminal->write("\n");
    }
}

$terminal->moveCursor()->center(-(mb_strlen($str) / 2))->write($str);
