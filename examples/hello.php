<?php

use MilesChou\Pherm\App;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = App::create()
    ->createTerminal()
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();

$str = 'Hello world!';

$terminal->cursor()->center(-(mb_strlen($str) / 2))->write($str);

$terminal->cursor()->bottom();
