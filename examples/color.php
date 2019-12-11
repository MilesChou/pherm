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
$terminal->cursor()->top();

$str = 'Hello 世界!';

$payload = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

foreach (range(16, 231) as $i => $bg) {
    $terminal->attribute(((int)$i % 36 < 18 ? 15 : 0), $bg)->write($payload[$i % 36]);

    if ($i % 36 === 35) {
        $terminal->write("\n");
    }
}

$terminal->cursor()->center(-(mb_strlen($str) / 2))->write($str);

$terminal->flush();
