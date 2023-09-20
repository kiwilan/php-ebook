# PHP eBook

![Banner with eReader picture in background and PHP eBook title](https://raw.githubusercontent.com/kiwilan/php-ebook/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks, comics and audiobooks.

-   eBooks: `.epub`, `.pdf`
-   Comics: `.cbz`, `.cbr`, `.cb7`, `.cbt` (metadata from [github.com/anansi-project](https://github.com/anansi-project))
-   Audiobooks: `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg`

_Supports Linux, macOS and Windows._

> **Note**
>
> This package favors eBooks in open formats such as `.epub` (from [IDPF](https://en.wikipedia.org/wiki/International_Digital_Publishing_Forum)) or `.cbz` (from [CBA](https://en.wikipedia.org/wiki/Comic_book_archive)) and which be parsed with native PHP, so for the best possible experience we recommend converting the eBooks you use. If you want to know more about eBook ecosystem, you can read [documentation](https://github.com/kiwilan/php-ebook/blob/main/docs/README.md).

## About

This package was built for [`bookshelves-project/bookshelves`](https://github.com/bookshelves-project/bookshelves), a web app to handle eBooks.

## Requirements

-   **PHP version** `>=8.1`
-   **PHP extensions**:
    -   [`zip`](https://www.php.net/manual/en/book.zip.php) (native, optional) for `.EPUB`, `.CBZ`
    -   [`phar`](https://www.php.net/manual/en/book.phar.php) (native, optional) for `.CBT`
    -   [`rar`](https://www.php.net/manual/en/book.rar.php) (optional) for `.CBR` ([`p7zip`](https://www.7-zip.org/) binary can be used instead)
    -   [`imagick`](https://www.php.net/manual/en/book.imagick.php) (optional) for `.PDF`
    -   [`intl`](https://www.php.net/manual/en/book.intl.php) (native, optional) for `Transliterator` for better slugify
    -   [`fileinfo`](https://www.php.net/manual/en/book.fileinfo.php) (native, optional) for better detection of file type
-   To know more about requirements, see [Supported formats](#supported-formats).

> **Warning**
>
> Works with [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary could be necessary.
> Some guides to install these requirements are available on [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive#requirements).

## Features

-   Support multiple formats, see [Supported formats](#supported-formats)
-   🔎 Read metadata from eBooks, comics, and audiobooks
-   🖼️ Extract covers from eBooks, comics, and audiobooks
-   📚 Support metadata
    -   eBooks: `EPUB` v2 and v3 from [IDPF](https://idpf.org/) with `calibre:series` from [Calibre](https://calibre-ebook.com/) | `MOBI` from Mobipocket (and derivatives) | `FB2` from [FictionBook](https://en.wikipedia.org/wiki/FictionBook)
    -   Comics: `CBAM` (Comic Book Archive Metadata) : `ComicInfo.xml` format from _ComicRack_ and maintained by [`anansi-project`](https://github.com/anansi-project/comicinfo)
    -   `PDF` with [`smalot/pdfparser`](https://github.com/smalot/pdfparser)
    -   Audiobooks: `ID3`, `vorbis` and `flac` tags with [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio)
-   🔖 Chapters extraction (`EPUB` only)
-   📦 `EPUB` and `CBZ` creation supported
<!-- -   📝 `EPUB` and `CBZ` metadata update supported -->
-   Works perfectly with [`kiwilan/php-opds`](https://github.com/kiwilan/php-opds): PHP package to generate OPDS feeds (not included)

### Roadmap

-   [ ] More formats support: `.djvu`
-   [ ] Better `.epub` creation support
-   [ ] Add `.epub` metadata update support

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With eBook files or audiobook files (to know more about formats, see [Supported formats](#supported-formats)).

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

To know if eBook is valid, you can use `isValid()` static method, before `read()`.

```php
use Kiwilan\Ebook\Ebook;

$isValid = Ebook::isValid('path/to/ebook.epub');
```

To get additional data, you can use these methods:

```php
$ebook->getMetadata(); // ?EbookMetadata => metadata with parsers
$ebook->getMetaTitle(); // ?MetaTitle, with slug and sort properties for `title` and `series`
$ebook->getFormat(); // ?EbookFormatEnum => `epub`, `pdf`, `cba`
$ebook->getCover(); // ?EbookCover => cover of book
```

To access to archive of eBook, you can use `getArchive()` method. You can find more informations about archive in [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive).

```php
$ebook->getArchive(); // ?BaseArchive => archive of book from `kiwilan/php-archive`
```

And to test if some data exists:

```php
$ebook->isArchive(); // bool => `true` if `EPUB`, `CBA`
$ebook->isAudio(); // bool => `true` if `mp3`, `m4a`, `m4b`, `flac`, `ogg`
$ebook->hasMetadata(); // bool => `true` if metadata exists
$ebook->hasCover(); // bool => `true` if cover exists
$ebook->isBadFile(); // bool => `true` if file is not readable
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
$epub->getOpf(); // ?OpfItem => {`metadata`: array, `manifest`: array, `spine`: array, `guide`: array, `epubVersion`: ?int, `filename`: ?string, `dcTitle`: ?string, `dcCreators`: BookAuthor[], `dcContributors`: BookContributor[], `dcDescription`: ?string, `dcPublisher`: ?string, `dcIdentifiers`: BookIdentifier[], `dcDate`: ?DateTime, `dcSubject`: string[], `dcLanguage`: ?string, `dcRights`: array, `meta`: BookMeta[], `coverPath`: ?string, `contentFile`: string[]}
$epub->getNcx(); // ?NcxItem => {`head`: NcxItemHead[]|null, `docTitle`: ?string, `navPoints`: NcxItemNavPoint[]|null, `version`: ?string, `lang`: ?string}
$epub->getChapters(); // EpubChapter[] => {`label`: string, `source`: string, `content`: string}[]
$epub->getHtml(); // EpubHtml[] => {`filename`: string, `head`: ?string, `body`: ?string}[]
$epub->getFiles(); // string[] => all files in EPUB
```

> **Note**
>
> For performance reasons, with `ncx`, `html` and `chapters` are only available on demand. If you use `var_dump` to check metadata, these properties will be `null`.

### Creation

You can create an EPUB or CBZ file with `create()` static method.

> **Note**
>
> Only `EPUB` and `CBZ` are supported for creation.

```php
use Kiwilan\Ebook\Ebook;

$creator = Ebook::create('path/to/ebook.epub');

// Build manually
$creator->addFromString('mimetype', 'application/epub+zip')
    ->addFromString('META-INF/container.xml', '<?xml version="1.0" encoding="UTF-8" standalone="no" ?><container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container"><rootfiles><rootfile full-path="OEBPS/content.opf" media-type="application/oebps-package+xml"/></rootfiles></container>')
    ->save();

// Build from files
$creator->addFile('mimetype', 'path/to/mimetype')
    ->addFile('META-INF/container.xml', 'path/to/container.xml')
    ->save();

// Build from directory
$creator->addDirectory('./', 'path/to/directory')
    ->save();
```

## Supported formats

There is a lot of different formats for eBooks and comics, if you want to know more about:

-   [Comparison of e-book formats](https://en.wikipedia.org/wiki/Comparison_of_e-book_formats) for eBooks
-   [Comic book archive](https://en.wikipedia.org/wiki/Comic_book_archive) for comics
-   Amazing [MobileRead wiki](https://wiki.mobileread.com/wiki/Category:Formats)

|       Name       |               Extensions                | Supported |     Notes     |                                   Uses                                   |                                Support cover                                | Support series |
| :--------------: | :-------------------------------------: | :-------: | :-----------: | :----------------------------------------------------------------------: | :-------------------------------------------------------------------------: | :------------: |
|   EPUB (IDPF)    |                 `.epub`                 |    ✅     |               |        Native [`zip`](https://www.php.net/manual/en/book.zip.php)        |                                     ✅                                      |       ✅       |
| Kindle (Amazon)  |     `.azw`, `.azw3`, `.kf8`, `.kfx`     |    ✅     | _proprietary_ | Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php) |                ✅ (See [MOBI cover note](#mobi-cover-note))                 |       ❌       |
|    Mobipocket    |             `.mobi`, `.prc`             |    ✅     | _deprecated_  | Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php) |                ✅ (See [MOBI cover note](#mobi-cover-note))                 |       ❌       |
|       PDF        |                 `.pdf`                  |    ✅     |               |   [`smalot/pdfparser`](https://github.com/smalot/pdfparser) (included)   |      Uses [`imagick`](https://www.php.net/manual/en/book.imagick.php)       |       ❌       |
|  iBook (Apple)   |                `.ibooks`                |    ❌     | _proprietary_ |                                                                          |                                     N/A                                     |      N/A       |
|       DjVu       |             `.djvu`, `.djv`             |    ❌     |               |                                                                          |                                     N/A                                     |      N/A       |
| Rich Text Format |                 `.rtf`                  |    ❌     |               |                                                                          |                                     N/A                                     |      N/A       |
|   FictionBook    |                 `.fb2`                  |    ✅     |               | Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php) |                                     ✅                                      |       ✅       |
| Broadband eBooks |             `.lrf`, `.lrx`              |    ❌     |               |                                                                          |                                     N/A                                     |      N/A       |
|    Palm Media    |                 `.pdb`                  |    ❌     |               |                                                                          |                                     N/A                                     |      N/A       |
|    Comics CBZ    |                 `.cbz`                  |    ✅     |               |                                                                          |                                     ✅                                      |       ✅       |
|    Comics CBR    |                 `.cbr`                  |    ✅     |               |                                                                          |                                     ✅                                      |       ✅       |
|    Comics CB7    |                 `.cb7`                  |    ✅     |               |                                                                          |                                     ✅                                      |       ✅       |
|    Comics CBT    |                 `.cbt`                  |    ✅     |               |                                                                          |                                     ✅                                      |       ✅       |
|      Audio       | `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg` |    ✅     |               |     See [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio)      | [Depends of format](https://github.com/kiwilan/php-audio#supported-formats) |       ❌       |

### MOBI cover note

Mobipocket files and derivatives (`.mobi`, `.prc`, `.azw`, `.azw3`, `.kf8`, `.kfx`) can have a cover image embedded in the file. With native solution of `php-ebook` cover could be extracted but resolution is not good. Best solution is to convert file with [`calibre`](https://calibre-ebook.com/) and use `EPUB` format.

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
