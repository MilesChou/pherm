<?php

use MilesChou\Pherm\App;

include_once __DIR__ . '/../vendor/autoload.php';

$terminal = App::create()
    ->createTerminal()
    ->enableInstantOutput()
    ->bootstrap();

$terminal->clear()->cursor()->center();
sleep(1);

$terminal->cursor()->top();
$terminal->write(implode(',', $terminal->current()));
sleep(1);

$terminal->cursor()->top($terminal->width() / 2);
$str = implode(',', $terminal->current());
$terminal->cursor()->top($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->cursor()->top($terminal->width());
$str = implode(',', $terminal->current());
$terminal->cursor()->top($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->cursor()->middle();
$terminal->write(implode(',', $terminal->current()));
sleep(1);

$terminal->cursor()->center();
$str = implode(',', $terminal->current());
$terminal->cursor()->center(-(strlen($str) / 2))->write($str);
sleep(1);

$terminal->cursor()->middle($terminal->width());
$str = implode(',', $terminal->current());
$terminal->cursor()->middle($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->cursor()->bottom();
$terminal->write(implode(',', $terminal->current()));
sleep(1);

$terminal->cursor()->bottom($terminal->width() / 2);
$str = implode(',', $terminal->current());
$terminal->cursor()->bottom($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->cursor()->end();
$str = implode(',', $terminal->current());
$terminal->cursor()->end(strlen($str) -1)->write($str);
sleep(1);
