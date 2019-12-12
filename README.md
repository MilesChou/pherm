# Pherm - the terminal utility written by PHP

[![License][license-svg]][license-link]
[![Build Status][travis-svg]][travis-link]
[![Coverage Status][coverage-svg]][coverage-link]
[![Codacy Badge][codacy-svg]][codacy-link]

> This repo is fork from [`php-school/terminal`](https://github.com/php-school/terminal) 

Small utility to help provide a simple, consist API for terminal interaction.

See [examples](/examples) to know how to use.

[license-svg]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[license-link]: https://github.com/oidcphp/core/blob/master/LICENSE
[travis-svg]: https://travis-ci.com/MilesChou/pherm.svg?branch=master
[travis-link]: https://travis-ci.com/MilesChou/pherm
[coverage-svg]: https://codecov.io/gh/MilesChou/pherm/branch/master/graph/badge.svg
[coverage-link]: https://codecov.io/gh/MilesChou/pherm
[codacy-svg]: https://api.codacy.com/project/badge/Grade/3d1e8acb28da4daf94b649f859a271b7
[codacy-link]: https://www.codacy.com/manual/MilesChou/pherm

## Usage

Hello world example:

```php
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
```

## Credits

* [MilesChou](https://github.com/MilesChou)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
