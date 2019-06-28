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

$terminal->moveCursor()->top();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->moveCursor()->top($terminal->width() / 2);
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->top($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->moveCursor()->top($terminal->width());
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->top($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->moveCursor()->middle();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->moveCursor()->center();
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->center(-(strlen($str) / 2))->write($str);
sleep(1);

$terminal->moveCursor()->middle($terminal->width());
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->middle($terminal->width() - strlen($str) + 1)->write($str);
sleep(1);

$terminal->moveCursor()->bottom();
$terminal->write(implode(',', $terminal->cursor()->current()));
sleep(1);

$terminal->moveCursor()->bottom($terminal->width() / 2);
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->bottom($terminal->width() / 2 - strlen($str) / 2)->write($str);
sleep(1);

$terminal->moveCursor()->end();
$str = implode(',', $terminal->cursor()->current());
$terminal->moveCursor()->end(strlen($str) -1)->write($str);
sleep(1);
