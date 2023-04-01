# PHP eBook

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`, `.pdf`).

Supports Linux, macOS and Windows.

> **Warning**
>
> Works with [kiwilan/php-ebook](https://github.com/kiwilan/php-ebook), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [p7zip](https://www.7-zip.org/) binary could be necessary, see [Requirements](#requirements).

## About

This package was built for [bookshelves-project/bookshelves-back](https://github.com/bookshelves-project/bookshelves-back), a web app to handle eBooks.

## Requirements

-   PHP >= 8.1
-   Depends of CBA and features you want to use

|  Type  | Native |                                                                                       Dependency                                                                                       |
| :----: | :----: | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| `.cbz` |   ✅   |                                                                                          N/A                                                                                           |
| `.cbt` |   ✅   |                                                                                          N/A                                                                                           |
| `.cbr` |   ❌   |                                        [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary                                        |
| `.cb7` |   ❌   |                                                                        [`p7zip`](https://www.7-zip.org/) binary                                                                        |
| `.pdf` |   ✅   |                                                Optional (for extraction) [`imagick` PHP extension](https://github.com/Imagick/imagick)                                                 |
|  ALL   |   ❌   | [`p7zip`](https://www.7-zip.org/) binary ([`rar` PHP extension](https://github.com/cataphract/php-rar) and [`imagick` PHP extension](https://github.com/Imagick/imagick) are optional) |

> **Note**
>
> Here you can read some installation guides for dependencies
>
> -   [`p7zip` guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d)
> -   [`rar` PHP extension guide](https://gist.github.com/ewilan-riviere/3f4efd752905abe24fd1cd44412d9db9#winrar)
> -   [`imagick` PHP extension guide](https://gist.github.com/ewilan-riviere/3f4efd752905abe24fd1cd44412d9db9#imagemagick)

> **Warning**
>
> -   **On macOS**, for `.rar` extract, you have to [install `rar` binary](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d#macos) to extract files, `p7zip` not support `.rar` extraction.
> -   **On Windows**, for `.pdf` extract, [`imagick` PHP extension](https://github.com/Imagick/imagick) have to work but **my tests failed on this feature**. So to extract PDF pages I advice to use [WSL](https://learn.microsoft.com/en-us/windows/wsl/install).

If you want more informations, you can read [kiwilan/php-archive](https://github.com/kiwilan/php-archive).

## Features

-   Read metadata from eBooks
-   Extract covers from eBooks
-   Support metadata
    -   EPUB v2 and v3 from [IDPF](https://idpf.org/)
    -   `calibre:series` for EPUB from [Calibre](https://calibre-ebook.com/)
    -   `calibre:series_index` for EPUB from [Calibre](https://calibre-ebook.com/)
    -   `ComicInfo.xml` (CBAM) format from ComicRack and maintained by [anansi-project](https://github.com/anansi-project/comicinfo)
    -   PDF by [smalot/pdfparser](https://github.com/smalot/pdfparser)

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With eBook files (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`, `.pdf`)

```php
$ebook = Ebook::read('path/to/archive.epub');
$book = $ebook->book(); // BookEntity

$book->path(); // string
$book->title(); // string
$book->authors(); // BookCreator[] (name: string, role: string)
$book->authorFirst(); // First BookCreator (name: string, role: string)
$book->description(); // string
$book->contributor(); // string
$book->rights(); // string
$book->publisher(); // string
$book->identifiers(); // BookIdentifier[] (content: string, type: string)
$book->date(); // DateTime
$book->language(); // string
$book->tags(); // string[] => `subject` in EPUB, `keywords` in PDF, `genres` in CBA
$book->series(); // string => `calibre:series` in EPUB, `series` in CBA
$book->volume(); // int => `calibre:series_index` in EPUB, `number` in CBA
$book->rating(); // float => `rating` in CBA
$book->pageCount(); // int => computed from words in EPUB, `pageCount` in PDF, `pageCount` in CBA
$book->editors(); // string[] => `editors` in CBA
$book->review(); // string => `review` in CBA
$book->web(); // string => `web` in CBA
$book->manga(); // MangaEnum => `manga` in CBA | Addtional data about mangas
$book->isBlackAndWhite(); // bool => `blackAndWhite` in CBA
$book->ageRating(); // AgeRatingEnum => `ageRating` in CBA | Addtional data about age rating
$book->comicMeta(); // ComicMeta => Addtional data for CBA
$book->cover(); // string => cover as string
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
