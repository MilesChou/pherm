<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear();

// Top is base on 1

// Backward col is valid but it not good to control.
// $terminal->move()->top(-3)->write('*');
// $terminal->move()->top(-2)->write('+');
// $terminal->move()->top(-1)->write('-');
// $terminal->move()->top(0)->write('0');
$terminal->moveCursor()->top(1)->write('1');
$terminal->moveCursor()->top(2)->write('2');
$terminal->moveCursor()->top(3)->write('3');
$terminal->moveCursor()->top(4)->write('4');
$terminal->moveCursor()->top(5)->write('5');

sleep(1);

// $terminal->move()->row(-3)->write('*');
// $terminal->move()->row(-2)->write('+');
// $terminal->move()->row(-1)->write('-');
// $terminal->move()->row(0)->write('0');
$terminal->moveCursor()->row(1)->write('1');
$terminal->moveCursor()->row(2)->write('2');
$terminal->moveCursor()->row(3)->write('3');
$terminal->moveCursor()->row(4)->write('4');
$terminal->moveCursor()->row(5)->write('5');

sleep(1);

// End using width / height size default

// Forward col is valid but it not good to control.
// $terminal->move()->end(-3, 0)->write('*');
// $terminal->move()->end(-2, 0)->write('+');
// $terminal->move()->end(-1, 0)->write('-');
$terminal->moveCursor()->end(0, 0)->write('0');
$terminal->moveCursor()->end(1, 0)->write('1');
$terminal->moveCursor()->end(2, 0)->write('2');
$terminal->moveCursor()->end(3, 0)->write('3');
$terminal->moveCursor()->end(4, 0)->write('4');
$terminal->moveCursor()->end(5, 0)->write('5');

sleep(1);

//$terminal->move()->end(0, -3)->write('*');
//$terminal->move()->end(0, -2)->write('+');
//$terminal->move()->end(0, -1)->write('-');
$terminal->moveCursor()->end(0, 0)->write('0');
$terminal->moveCursor()->end(0, 1)->write('1');
$terminal->moveCursor()->end(0, 2)->write('2');
$terminal->moveCursor()->end(0, 3)->write('3');
$terminal->moveCursor()->end(0, 4)->write('4');
$terminal->moveCursor()->end(0, 5)->write('5');

sleep(1);

$terminal->moveCursor()->bottom();
