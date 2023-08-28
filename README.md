# PHP eBook

![Banner with eReader picture in background and PHP eBook title](https://raw.githubusercontent.com/kiwilan/php-ebook/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`, `.pdf`) and audiobooks (`.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg`).

_Supports Linux, macOS and Windows._

> **Note**
>
> This package favors eBooks in open formats such as `.epub` or `.cbz` and which be parsed with native PHP, so for the best possible experience we recommend converting the eBooks you use. If you want to know more about eBook ecosystem, you can read [documentation](https://github.com/kiwilan/php-ebook/blob/main/docs/README.md).

## Table of Contents

-   [About](#about)
-   [Requirements](#requirements)
-   [Features](#features)
    -   [Roadmap](#roadmap)
-   [Installation](#installation)
-   [Usage](#usage)
    -   [Main](#main)
    -   [Metadata](#metadata)
    -   [MetaTitle](#metatitle)
    -   [Cover](#cover)
-   [Testing](#testing)
-   [Changelog](#changelog)
-   [Credits](#credits)
-   [License](#license)

## About

This package was built for [`bookshelves-project/bookshelves`](https://github.com/bookshelves-project/bookshelves), a web app to handle eBooks.

## Requirements

-   **PHP version** >= _8.1_
-   **PHP extensions**:
    -   [`zip`](https://www.php.net/manual/en/book.zip.php) (native, optional) for `.EPUB`, `.CBZ`
    -   [`rar`](https://www.php.net/manual/en/book.rar.php) (optional) for `.CBR`
    -   [`imagick`](https://www.php.net/manual/en/book.imagick.php) (optional) for `.PDF`
    -   [`intl`](https://www.php.net/manual/en/book.intl.php) (native, optional) for `Transliterator`
    -   [`fileinfo`](https://www.php.net/manual/en/book.fileinfo.php) (native, optional) for better detection of file type

|                  Type                   | Supported |                                               Requirement                                                |         Uses         |
| :-------------------------------------: | :-------: | :------------------------------------------------------------------------------------------------------: | :------------------: |
|             `.epub`, `.cbz`             |    ✅     |                                                   N/A                                                    |         N/A          |
|                 `.cbt`                  |    ✅     |                                                   N/A                                                    |         N/A          |
|                 `.cbr`                  |    ✅     | [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary | PHP `rar` or `p7zip` |
|                 `.cb7`                  |    ✅     |                                 [`p7zip`](https://www.7-zip.org/) binary                                 |    `p7zip` binary    |
|                 `.pdf`                  |    ✅     |         Optional (for extraction) [`imagick` PHP extension](https://github.com/Imagick/imagick)          |  `smalot/pdfparser`  |
| `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg` |    ✅     |                                                   N/A                                                    | `kiwilan/php-audio`  |

> **Warning**
>
> Works with [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary could be necessary.
> Some guides to install these requirements are available on [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive#requirements).

## Features

-   🔎 Read metadata from **eBooks** and **audiobooks**
-   🖼️ Extract covers from **eBooks** and **audiobooks**
-   📚 Support metadata
    -   eBooks:
        -   `EPUB` v2 and v3 from [IDPF](https://idpf.org/) with `calibre:series` and from [Calibre](https://calibre-ebook.com/)
    -   Comics:
        -   `CBAM` (Comic Book Archive Metadata) : `ComicInfo.xml` format from _ComicRack_ and maintained by [`anansi-project`](https://github.com/anansi-project/comicinfo)
    -   `PDF` with [`smalot/pdfparser`](https://github.com/smalot/pdfparser)
    -   Audiobooks: `ID3`, `vorbis` and `flac` tags with [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio)
-   🔖 Chapters extraction (`EPUB` only)

<!-- -   📦 `EPUB` and `CBZ` creation supported -->
<!-- -   📝 `EPUB` and `CBZ` metadata update supported -->

### Roadmap

-   [ ] Add `.mobi`, `.azw`, `.azw3` support
    -   https://stackoverflow.com/questions/11817047/php-library-to-parse-mobi
    -   https://wiki.mobileread.com/wiki/MOBI
-   [ ] Add `.djvu` support
-   [ ] Add `.fb2`, `.lrf`, `.pdb`, `.snb` support
-   [ ] Add `.epub` creation support
-   [ ] Add `.epub` metadata update support

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

### Main

With eBook files (`.epub`, `.cbz`, `.cba`, `.cbr`, `.cb7`, `.cbt`, `.pdf`) or audiobook files (`mp3`, `m4a`, `m4b`, `flac`, `ogg`).

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$ebook->getPath(); // string => path to ebook
$ebook->getFilename(); // string => filename of ebook
$ebook->getExtension(); // string => extension of ebook
$ebook->getTitle(); // string
$ebook->getAuthors(); // BookAuthor[] (`name`: string, `role`: string)
$ebook->getAuthorMain(); // ?BookAuthor => First BookAuthor (`name`: string, `role`: string)
$ebook->getDescription(); // ?string
$ebook->getDescriptionHtml(); // ?string
$ebook->getCopyright(); // ?string
$ebook->getPublisher(); // ?string
$ebook->getIdentifiers(); // BookIdentifier[] (`value`: string, `scheme`: string)
$ebook->getPublishDate(); // ?DateTime
$ebook->getLanguage(); // ?string
$ebook->getTags(); // string[] => `subject` in EPUB, `keywords` in PDF, `genres` in CBA
$ebook->getSeries(); // ?string => `calibre:series` in EPUB, `series` in CBA
$ebook->getVolume(); // ?int => `calibre:series_index` in EPUB, `number` in CBA
```

For pages count, you can use these methods:

```php
$ebook->getPagesCount(); // ?int => estimated pages count (250 words by page) in `EPUB`, `pageCount` in PDF, `pageCount` in CBA
$ebook->getWordsCount(); // ?int => words count in `EPUB`
```

> **Note**
>
> For performance reasons, with `EPUB`, `pagesCount` and `wordsCount` are only available on demand. If you use `var_dump` to check eBook, these properties will be `null`.

Some metadata can be stored into `extras()` method, without typing, directly from metadata.

```php
$ebook->getExtras(); // array<string, mixed> => additional data for book
$ebook->getExtra(string $key); // mixed => safely extract data from `extras` array
```

To get additional data, you can use these methods:

```php
$ebook->getMetadata(); // ?EbookMetadata => metadata with parsers
$ebook->getMetaTitle(); // ?MetaTitle, with slug and sort properties for `title` and `series`
$ebook->getFormat(); // ?EbookFormatEnum => `epub`, `pdf`, `cba`
$ebook->getCover(); // ?EbookCover => cover of book
$ebook->getArchive(); // ?BaseArchive => archive of book from `kiwilan/php-archive`
```

And to test if some data exists:

```php
$ebook->isArchive(); // bool => `true` if `EPUB`, `CBA`
$ebook->isAudio(); // bool => `true` if `mp3`, `m4a`, `m4b`, `flac`, `ogg`
$ebook->hasMetadata(); // bool => `true` if metadata exists
$ebook->hasCover(); // bool => `true` if cover exists
```

### Metadata

`Ebook::class` contains many informations but if you want to access to raw metadata, `metadata()` method is available.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$metadata = $ebook->getMetadata();

$metadata->getModule(); // Used into parsing can be any of `EbookModule::class`
$metadata->getEpub(); // `EpubMetadata::class`
$metadata->getPdf(); // `PdfMetadata::class`
$metadata->getCba(); // `CbaMetadata::class`
$metadata->getAudiobook(); // `AudiobookMetadata::class`

$metadata->isEpub(); // bool
$metadata->isPdf(); // bool
$metadata->isCba(); // bool
$metadata->isAudiobook(); // bool
```

### MetaTitle

Can be set if book's title is not null.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');
$metaTitle = $ebook->getMetaTitle(); // ?MetaTitle

$metaTitle->getSlug(); // string => slugify title, like `the-clan-of-the-cave-bear`
$metaTitle->getSlugSort(); // string => slugify title without determiners, like `clan-of-the-cave-bear`
$metaTitle->getSlugLang(); // string => slugify title with language and type, like `the-clan-of-the-cave-bear-epub-en`

$metaTitle->getSerieSlug(); // ?string => slugify series title, like `earths-children`
$metaTitle->getSerieSort(); // ?string => slugify series title without determiners, like `earths-children`
$metaTitle->getSerieLang(); // ?string => slugify series title with language and type, like `earths-children-epub-en`

$metaTitle->getSlugSortWithSerie(); // string => slugify title with series title and volume, like `earths-children-01_clan-of-the-cave-bear`
$metaTitle->getUniqueFilename(); // string => unique filename for storage, like `jean-m-auel-earths-children-01-clan-of-the-cave-bear-en-epub`
```

### Cover

Cover can be extracted from ebook.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');
$cover = $ebook->getCover(); // ?EbookCover

$cover->getPath(); // ?string => path to cover
$cover->getContent(bool $toBase64 = false); // ?string => content of cover, if `$toBase64` is true, return base64 encoded content
```

> **Note**
>
> -   For `PDF`, cover can only be extracted if [`imagick` PHP extension](https://www.php.net/manual/en/book.imagick.php).
> -   For Audiobook, cover can be extracted with `mp3` but not with other formats.

### Formats specifications

#### EPUB

With `EPUB`, metadata are extracted from `OPF` file, `META-INF/container.xml` files, you could access to these metatada but you can also get chapters from `NCX` file. And with `chapters()` method you can merge `NCX` and `HTML` chapters to get full book chapters with `label`, `source` and `content`.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$epub = $ebook->getMetadata()?->getEpub();

$epub->getContainer(); // ?EpubContainer => {`opfPath`: ?string, `version`: ?string, `xml`: array}
$epub->getOpf(); // ?OpfMetadata => {`metadata`: array, `manifest`: array, `spine`: array, `guide`: array, `epubVersion`: ?int, `filename`: ?string, `dcTitle`: ?string, `dcCreators`: BookAuthor[], `dcContributors`: BookContributor[], `dcDescription`: ?string, `dcPublisher`: ?string, `dcIdentifiers`: BookIdentifier[], `dcDate`: ?DateTime, `dcSubject`: string[], `dcLanguage`: ?string, `dcRights`: array, `meta`: BookMeta[], `coverPath`: ?string, `contentFile`: string[]}
$epub->getNcx(); // ?NcxMetadata => {`head`: NcxMetadataHead[]|null, `docTitle`: ?string, `navPoints`: NcxMetadataNavPoint[]|null, `version`: ?string, `lang`: ?string}
$epub->getChapters(); // EpubChapter[] => {`label`: string, `source`: string, `content`: string}[]
$epub->getHtml(); // EpubHtml[] => {`filename`: string, `head`: ?string, `body`: ?string}[]
$epub->getFiles(); // string[] => all files in EPUB
```

> **Note**
>
> For performance reasons, with `ncx`, `html` and `chapters` are only available on demand. If you use `var_dump` to check metadata, these properties will be `null`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [`spatie`](https://github.com/spatie) for `spatie/package-skeleton-php`
-   [`kiwilan`](https://github.com/kiwilan) for `kiwilan/php-archive`, `kiwilan/php-audio`, `kiwilan/php-xml-reader`
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-ebook.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/php-ebook
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
