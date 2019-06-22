<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->bootstrap();

$terminal->clear();

$str = 'Hello world!';

$terminal->moveCursorToCenter(-(mb_strlen($str) / 2))
    ->write('Hello world!')
    ->moveCursorToDown();
