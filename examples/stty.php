<?php

include_once __DIR__ . '/../vendor/autoload.php';

$data = (new \MilesChou\Pherm\Stty())->parseAll();

dump($data);
