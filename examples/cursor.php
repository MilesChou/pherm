<?php

use Illuminate\Container\Container;
use MilesChou\Pherm\Contracts\Input;
use MilesChou\Pherm\Contracts\Output;
use MilesChou\Pherm\Input\InputStream;
use MilesChou\Pherm\Output\OutputStream;
use MilesChou\Pherm\Terminal;

include_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->instance(Input::class, new InputStream());
$container->instance(Output::class, new OutputStream());

$terminal = (new Terminal($container))
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
