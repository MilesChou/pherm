# Terminal Utility

[![Build Status](https://travis-ci.com/MilesChou/pherm.svg?branch=master)](https://travis-ci.com/MilesChou/pherm)
[![Coverage Status](https://coveralls.io/repos/github/MilesChou/pherm/badge.svg?branch=master)](https://coveralls.io/github/MilesChou/pherm?branch=master)

> This repo is fork from [`php-school/terminal`](https://github.com/php-school/terminal) 

Small utility to help provide a simple, consise API for terminal interaction.

See [examples](/examples) to know how to use.

## Usage

Hello world example:

```php
$terminal = (new Terminal(new InputStream(), new OutputStream()))
    ->bootstrap();

$terminal->clear();

$str = 'Hello world!';

$terminal->move()->center(-(mb_strlen($str) / 2))
    ->write('Hello world!')
    ->move()->down();
```
