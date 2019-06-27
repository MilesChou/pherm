<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();

$terminal->move()->top();

$payload = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

foreach (range(16, 231) as $i => $bg) {
    $terminal->write($payload[$i % 36], ((int)$i % 36 < 18 ? 15 : 0), $bg);

    if ($i % 36 === 35) {
        $terminal->write("\n");
    }
}

$terminal->move()->center(-(mb_strlen('Hello world!') / 2))->write('Hello world!');

$terminal->flush();
