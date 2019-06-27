<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear()->cursor()->moveCenter();

sleep(1);
$terminal->move()->top()->write('1');

sleep(1);
$terminal->move()->top($terminal->width() / 2)->write('2');

sleep(1);
$terminal->move()->top($terminal->width())->write('3');

sleep(1);
$terminal->move()->middle()->write('4');

sleep(1);
$terminal->move()->center()->write('5');

sleep(1);
$terminal->move()->middle($terminal->width())->write('6');

sleep(1);
$terminal->move()->bottom()->write('7');

sleep(1);
$terminal->move()->bottom($terminal->width() / 2)->write('8');

sleep(1);
$terminal->move()->end()->write('9');

sleep(3);
