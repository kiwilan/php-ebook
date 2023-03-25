# PHP eBook

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`, `.pdf`).

> **Warning**
>
> Works with [p7zip](https://www.7-zip.org/) binary, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d) to install it on your system.

## About

TODO

## Requirements

-   PHP >= 8.1
-   `p7zip` binary, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d)
-   Optional:
    -   `macOS` only: `rar` binary for `.rar` file extract method, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d#macos)
    -   [`imagick` PECL extension](https://github.com/Imagick/imagick): for PDF `extract` method, you can check [this guide](https://gist.github.com/ewilan-riviere/3f4efd752905abe24fd1cd44412d9db9#imagemagick)

## Features

TODO

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With eBook files (`epub`, `cbz`, `cbr`, `cb7`, `cbt`, `pdf`)

```php
$ebook = Ebook::make('path/to/archive.epub');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Kiwilan](https://github.com/kiwilan)
-   [All Contributors](../../contributors)
-   [spatie](https://github.com/spatie) for `spatie/package-skeleton-php`
-   EPUB [The Clan of the Cave Bear](https://www.goodreads.com/book/show/40611463-the-clan-of-the-cave-bear) by Jean M. Auel
-   [epub3-samples](https://github.com/IDPF/epub3-samples) by IDPF

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-ebook.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/steward-laravel
[php-version-src]: https://img.shields.io/static/v1?style=flat-square&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/php-ebook.svg?style=flat-square&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/php-ebook
[license-src]: https://img.shields.io/github/license/kiwilan/php-ebook.svg?style=flat-square&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/php-ebook/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/php-ebook/run-tests.yml?branch=main&label=tests&style=flat-square&colorA=18181B
[tests-href]: https://packagist.org/packages/kiwilan/php-ebook
[codecov-src]: https://codecov.io/gh/kiwilan/php-ebook/branch/main/graph/badge.svg?token=P9XIK2KV9G
[codecov-href]: https://codecov.io/gh/kiwilan/php-ebook
