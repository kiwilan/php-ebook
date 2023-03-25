# PHP eBook

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read and extract from archives (`.zip`, `.rar`, `.tar`, `.7z`) or `.pdf` with `p7zip` binary, designed to works with eBooks (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`).

> **Warning**
>
> Works with [p7zip](https://www.7-zip.org/) binary, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d) to install it on your system.

## About

This package was heavily inspired by [Gemorroj/Archive7z](https://github.com/Gemorroj/Archive7z) which is a wrapper is a wrapper of [p7zip-project/p7zip](https://github.com/p7zip-project/p7zip) a fork of `p7zip`. If you need to manage many archives, you should use `Gemorroj/Archive7z` instead. Current package is a wrapper of original `p7zip`, it's not powerful as `p7zip-project/p7zip` but easier to install.

Alternatives:

-   [Gemorroj/Archive7z](https://github.com/Gemorroj/Archive7z): handle many archives with [p7zip-project/p7zip](https://github.com/p7zip-project/p7zip) binary
-   [splitbrain/php-ebook](https://github.com/splitbrain/php-ebook): native PHP solution to handle `.zip` and `.tar` archives

### Why not use native PHP functions?

To handle `.zip` archives, it's easy with `ZipArchive` native class. But for other formats, it's really a pain. For `.rar` format, you need [PECL `rar`](https://github.com/cataphract/php-rar) extension which is not actively maintained. For `tar` format, you have many possibilities but it's really a pain to manage all of them, with `.gz`, `.bz2`, `.xz` and `.lzma` compression. And for `.7z` format with PHP, it's again a pain.

The binary `p7zip` is a really good solution to handle all of them. It's not a native PHP solution but it's easy to install on most of OS. This package is not an all-in-one solution but it's a good start to handle archives.

### What is the aim of this package?

I wanted to handle eBooks like `.epub` or `.cbz`. I needed to scan files into these archives and extract some files with a good performance. I extended to `.tar` compression formats because it's really easy to handle with `p7zip`. I handle PDF metadata with `smalot/pdfparser` for eBooks which are PDF format.

### Really works on any system?

It designed to works with any system with `p7zip` installed. But for `macOS`, `p7zip` is not able to handle `.rar` extraction, you have to install third library `rar`.

## Requirements

-   PHP >= 8.1
-   `p7zip` binary, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d)
-   Optional:
    -   `macOS` only: `rar` binary for `.rar` file extract method, you can check [this guide](https://gist.github.com/ewilan-riviere/85d657f9283fa6af255531d97da5d71d#macos)
    -   [`imagick` PECL extension](https://github.com/Imagick/imagick): for PDF `extract` method, you can check [this guide](https://gist.github.com/ewilan-riviere/3f4efd752905abe24fd1cd44412d9db9#imagemagick)

## Features

### Archives

-   List files
-   Content of file
-   [ ] Extract file
-   [ ] Extract all files
-   Find files
-   [ ] Extract files with a pattern
-   Count files
-   [ ] Create

### PDF

-   Content of any page as image
-   [ ] Extract any page as image
-   [ ] Extract all pages as images
-   Extract text content
-   Get metadata

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With archive file (`.zip`, `.rar`, `.tar`, `.7z`, `epub`, `cbz`, `cbr`, `cb7`, `cbt`, `tar.gz`)

```php
$archive = Archive::make('path/to/archive.zip');

$files = $archive->files(); // ArchiveItem[]
$count = $archive->count(); // int of files count
$content = $archive->contentFile('archive/cover.jpeg'); // string of file content
$images = $archive->findAll('jpeg'); // ArchiveItem[]
$specificFile = $archive->find('metadata.xml'); // ArchiveItem|null
$contentOfFile = $archive->contentFile($specificFile->path()); // string of `metadata.xml` file content
```

With PDF file

```php
$pdf = ArchivePdf::make('path/to/file.pdf');

$files = $pdf->metadata(); // PdfMetadata
$count = $pdf->count(); // int of PDF pages count
$content = $pdf->contentPage(index: 0, format: 'png', toBase64: true ); // string of PDF page index 0 as PNG base64 encoded (ImageMagick required)
$text = $pdf->text(); // string of PDF text content
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
-   [`smalot/pdfparser`](https://github.com/smalot/pdfparser) for PDF parser
-   [`7-zip`](https://www.7-zip.org/) for `p7zip` binary

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
