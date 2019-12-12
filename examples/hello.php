<?php

use Illuminate\Container\Container;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new Container()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();

$str = 'Hello world!';

$terminal->cursor()->center(-(mb_strlen($str) / 2))->write($str);

$terminal->cursor()->bottom();
