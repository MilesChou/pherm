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

$terminal->clear();

$str = 'Hello world!';

$terminal->cursor()->center(-(mb_strlen($str) / 2))->write($str);

$terminal->cursor()->bottom();
