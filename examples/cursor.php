<?php

use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear()->cursor()->center();
sleep(1);

$terminal->cursor()->top();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->cursor()->top($terminal->width() / 2);
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->top($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->cursor()->top($terminal->width());
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->top($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->cursor()->middle();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->cursor()->center();
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->center(-(strlen($str) / 2))->write($str);
sleep(1);

$terminal->cursor()->middle($terminal->width());
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->middle($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->cursor()->bottom();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->cursor()->bottom($terminal->width() / 2);
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->bottom($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->cursor()->end();
$str = implode(',', $terminal->cursor()->current());
$terminal->cursor()->end(strlen($str) -1)->write($str);
sleep(1);
