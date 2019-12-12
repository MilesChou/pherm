<?php

use Illuminate\Container\Container;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new Container()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();

// Top is base on 1

// Backward col is valid but it not good to control.
// $terminal->cursor()->top(-3)->write('*');
// $terminal->cursor()->top(-2)->write('+');
// $terminal->cursor()->top(-1)->write('-');
// $terminal->cursor()->top(0)->write('0');
$terminal->cursor()->top(1)->write('1');
$terminal->cursor()->top(2)->write('2');
$terminal->cursor()->top(3)->write('3');
$terminal->cursor()->top(4)->write('4');
$terminal->cursor()->top(5)->write('5');

sleep(1);

// $terminal->cursor()->row(-3)->write('*');
// $terminal->cursor()->row(-2)->write('+');
// $terminal->cursor()->row(-1)->write('-');
// $terminal->cursor()->row(0)->write('0');
$terminal->cursor()->row(1)->write('1');
$terminal->cursor()->row(2)->write('2');
$terminal->cursor()->row(3)->write('3');
$terminal->cursor()->row(4)->write('4');
$terminal->cursor()->row(5)->write('5');

sleep(1);

// End using width / height size default

// Forward col is valid but it not good to control.
// $terminal->cursor()->end(-3, 0)->write('*');
// $terminal->cursor()->end(-2, 0)->write('+');
// $terminal->cursor()->end(-1, 0)->write('-');
$terminal->cursor()->end(0, 0)->write('0');
$terminal->cursor()->end(1, 0)->write('1');
$terminal->cursor()->end(2, 0)->write('2');
$terminal->cursor()->end(3, 0)->write('3');
$terminal->cursor()->end(4, 0)->write('4');
$terminal->cursor()->end(5, 0)->write('5');

sleep(1);

//$terminal->cursor()->end(0, -3)->write('*');
//$terminal->cursor()->end(0, -2)->write('+');
//$terminal->cursor()->end(0, -1)->write('-');
$terminal->cursor()->end(0, 0)->write('0');
$terminal->cursor()->end(0, 1)->write('1');
$terminal->cursor()->end(0, 2)->write('2');
$terminal->cursor()->end(0, 3)->write('3');
$terminal->cursor()->end(0, 4)->write('4');
$terminal->cursor()->end(0, 5)->write('5');

sleep(1);

$terminal->cursor()->bottom();
