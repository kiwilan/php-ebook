# PHP eBook

![Banner with eReader picture in background and PHP eBook title](https://raw.githubusercontent.com/kiwilan/php-ebook/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks, comics and audiobooks.

> Because metadata are the key against chaos.

-   eBooks: `.epub`, `.pdf`, `.azw`, `.azw3`, `.kf8`, `.kfx`, `.mobi`, `.prc`, `.fb2`
-   Comics: `.cbz`, `.cbr`, `.cb7`, `.cbt` (metadata from [github.com/anansi-project](https://github.com/anansi-project))
-   Audiobooks: `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg` with external package[`kiwilan/php-audio`](https://github.com/kiwilan/php-audio) (**MUST** be installed separately)

To know more see [Supported formats](#supported-formats). _Supports Linux, macOS and Windows._

> [!NOTE]
>
> This package favors eBooks in open formats such as `.epub` (from [IDPF](https://en.wikipedia.org/wiki/International_Digital_Publishing_Forum)) or `.cbz` (from [CBA](https://en.wikipedia.org/wiki/Comic_book_archive)) and which be parsed with native PHP, so for the best possible experience we recommend converting the eBooks you use. If you want to know more about eBook ecosystem, you can read [documentation](https://github.com/kiwilan/php-ebook/blob/main/docs/README.md).

> [!WARNING]
>
> For DRM (Digital Rights Management) eBooks, in some cases you could read metadata but not contents (like HTML files for EPUB). To use all features, you have to use a software to remove DRM before using this package. For EPUB, you can use [calibre](https://calibre-ebook.com/) with [DeDRM plugin](https://github.com/noDRM/DeDRM_tools), [this guide](https://www.epubor.com/calibre-drm-removal-plugins.html) can help you.

## Requirements

-   **PHP version** `>=8.1`
-   **PHP extensions**:
    -   [`zip`](https://www.php.net/manual/en/book.zip.php) (native, optional) for `.EPUB`, `.CBZ`
    -   [`phar`](https://www.php.net/manual/en/book.phar.php) (native, optional) for `.CBT`
    -   [`rar`](https://github.com/cataphract/php-rar) (optional) for `.CBR` ([`p7zip`](https://www.7-zip.org/) binary can be used instead)
    -   [`imagick`](https://www.php.net/manual/en/book.imagick.php) (optional) for `.PDF` cover
    -   [`intl`](https://www.php.net/manual/en/book.intl.php) (native, optional) for `Transliterator` for better slugify
    -   [`fileinfo`](https://www.php.net/manual/en/book.fileinfo.php) (native, optional) for better detection of file type
-   **Binaries**
    -   [`p7zip`](https://www.7-zip.org/) (optional) binarys for `.CB7` (can handle `.CBR` too)
-   **Audiobooks**
    -   [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio) (optional) for `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg` (see [Supported formats](#supported-formats))
-   To know more about requirements, see [Supported formats](#supported-formats)

> [!NOTE]
>
> You have to install requirements only if you want to read metadata for these formats, e.g. if you want to read metadata from `.cbr` files, you have to install [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary. So all requirements for PHP extensions and binaries are optional.

> [!WARNING]
>
> Archives are handle with [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary could be necessary.
> Some guides to install these requirements are available on [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive#requirements).

## Features

-   Support multiple formats, see [Supported formats](#supported-formats)
-   🔎 Read metadata from eBooks, comics, and audiobooks
-   🖼️ Extract covers from eBooks, comics, and audiobooks
-   🎵 Works with audiobooks if [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio) is installed
-   📚 Support metadata
    -   eBooks: `EPUB` v2 and v3 from [IDPF](https://idpf.org/) with `calibre:series` from [Calibre](https://calibre-ebook.com/) | `MOBI` from Mobipocket (and derivatives) | `FB2` from [FictionBook](https://en.wikipedia.org/wiki/FictionBook)
    -   Comics: `CBAM` (Comic Book Archive Metadata) : `ComicInfo.xml` format from _ComicRack_ and maintained by [`anansi-project`](https://github.com/anansi-project/comicinfo)
    -   `PDF` with [`smalot/pdfparser`](https://github.com/smalot/pdfparser)
    -   Audiobooks: `ID3`, `vorbis` and `flac` tags with [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio) (not included), based on [audiobookshelf specifications](https://www.audiobookshelf.org/docs#book-audio-metadata)
-   🔖 Chapters extraction (`EPUB` only)
-   📦 `EPUB` and `CBZ` creation supported
<!-- -   📝 `EPUB` and `CBZ` metadata update supported -->
-   Works perfectly with [`kiwilan/php-opds`](https://github.com/kiwilan/php-opds): PHP package to generate OPDS feeds (not included)

### Roadmap

-   [ ] Better `.epub` creation support
-   [ ] Add `.epub` metadata update support
-   [ ] Add better handling of MOBI files: [`libmobi`](https://github.com/bfabiszewski/libmobi) and [`ebook-convert`](https://manual.calibre-ebook.com/generated/en/ebook-convert.html) from Calibre (fallback is available)
-   [ ] Add support of [`ebook-convert`](https://manual.calibre-ebook.com/generated/en/ebook-convert.html) from Calibre
-   [ ] Add suport for DJVU: [`djvulibre`](https://djvu.sourceforge.net/)

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With eBook files or audiobook\* files (to know more about formats, see [Supported formats](#supported-formats)).

\*: should be installed separately, see [Requirements](#requirements).

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
$ebook->getCopyright(); // ?string
$ebook->getPublisher(); // ?string
$ebook->getIdentifiers(); // BookIdentifier[] (`value`: string, `scheme`: string)
$ebook->getPublishDate(); // ?DateTime
$ebook->getLanguage(); // ?string
$ebook->getTags(); // string[] => `subject` in EPUB, `keywords` in PDF, `genres` in CBA
$ebook->getSeries(); // ?string => `calibre:series` in EPUB, `series` in CBA
$ebook->getVolume(); // ?int => `calibre:series_index` in EPUB, `number` in CBA
$ebook->getCreatedAt(); // ?DateTime => file modified date
$ebook->getSize(); // int => file size in bytes
$ebook->getSizeHumanReadable(); // string => file size in human readable format
```

For advanced description parsing, you can use `getDescriptionAdvanced()` method with `BookDescription` class.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$description = $ebook->getDescriptionAdvanced(); // BookDescription

$description->getDescription(); // string => raw description
$description->toHtml(?int $limit = null); // string => description formatted to HTML
$description->toString(?int $limit = null); // string => description formatted to plain text
$description->toStringMultiline(?int $limit = null); // string => description formatted to plain text with new lines
```

For pages count, you can use these methods:

```php
$ebook->getPagesCount(); // ?int => estimated pages count (250 words by page) in `EPUB`, `pageCount` in PDF, `pageCount` in CBA
$ebook->getWordsCount(); // ?int => words count in `EPUB`
```

> [!NOTE]
>
> For performance reasons, with `EPUB`, `pagesCount` and `wordsCount` are only available on demand. If you use `var_dump` to check eBook, these properties will be `null`.

Some metadata can be stored into `extras()` method, without typing, directly from metadata.

```php
$ebook->getExtras(); // array<string, mixed> => additional data for book
$ebook->getExtra(string $key); // mixed => safely extract data from `extras` array
```

> [!NOTE]
>
> For audiobooks, all metadata are stored into `extras` array, you will find duplicate with `Ebook::class` properties. See [Formats specifications](#formats-specifications) for more informations.

To know if eBook is valid, you can use `isValid()` static method, before `read()`.

```php
use Kiwilan\Ebook\Ebook;

$isValid = Ebook::isValid('path/to/ebook.epub');
```

To get additional data, you can use these methods:

```php
$ebook->getParser(); // ?EbookParser => Parser with modules
$ebook->getMetaTitle(); // ?MetaTitle, with slug for `title` and `series`
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
$ebook->isMobi(); // bool => `true` if Mobipocket derivatives
$ebook->isAudio(); // bool => `true` if `mp3`, `m4a`, `m4b`, `flac`, `ogg`
$ebook->hasCover(); // bool => `true` if cover exists
$ebook->hasMetadata(); // bool => `true` if metadata exists
$ebook->hasSeries(); // bool => `true` if series exists
$ebook->isBadFile(); // bool => `true` if file is not readable
```

### Metadata

`Ebook::class` contains many informations but if you want to access to raw metadata, `metadata()` method is available.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$parser = $ebook->getParser();

$parser->getModule(); // Used into parsing can be any of `EbookModule::class`

$parser->getAudiobook(); // `AudiobookModule::class`
$parser->getCba(); // `CbaModule::class`
$parser->getEpub(); // `EpubModule::class`
$parser->getFb2(); // `Fb2Module::class`
$parser->getMobi(); // `MobiModule::class`
$parser->getPdf(); // `PdfModule::class`

$parser->isAudiobook(); // bool
$parser->isCba(); // bool
$parser->isEpub(); // bool
$parser->isFb2(); // bool
$parser->isMobi(); // bool
$parser->isPdf(); // bool
```

### MetaTitle

Can be set if book's title is not null.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');
$metaTitle = $ebook->getMetaTitle(); // ?MetaTitle

$metaTitle->getSlug(); // string => slug title, like `lord-of-the-rings-en-01-fellowship-of-the-ring-j-r-r-tolkien-1954-epub`
$metaTitle->getSeriesSlug(); // ?string => slug series title, like `lord-of-the-rings-en`
```

You can customize slug with `MetaTitle::class`:

```php
$meta->getSlug(removeDeterminers: true, addSeries: true, addVolume: true, addAuthor: true, addYear: true, addExtension: true, addLanguage: true);
$meta->getSeriesSlug(removeDeterminers: true, addAuthor: false, addExtension: false, addLanguage: true);
```

### Cover

Cover can be extracted from ebook.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');
$cover = $ebook->getCover(); // ?EbookCover

$cover->getPath(); // ?string => path to cover
$cover->getContents(bool $toBase64 = false); // ?string => content of cover, if `$toBase64` is true, return base64 encoded content
```

> [!NOTE]
>
> -   For `PDF`, cover can only be extracted if [`imagick` PHP extension](https://www.php.net/manual/en/book.imagick.php).
> -   For Audiobook, cover can be extracted with [some formats](https://github.com/kiwilan/php-audio#supported-formats).

### Formats specifications

#### Audiobooks

For audiobooks, you have to install seperately [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio).

Specifications are based on [audiobookshelf](https://www.audiobookshelf.org/docs#book-audio-metadata) and [ID3](https://id3.org/ID3v2.4.0) tags. Metadata on audio files will be mapped as follows (second tag after "/" is a fallback):

Properties of `Audio::class` are:

| **ID3 Tag (case-insensitive)** | **eBook**                  |
| ------------------------------ | -------------------------- |
| `artist` / `album-artist`      | Authors\*                  |
| `album` / `title`              | Title                      |
| `subtitle`                     | Extra property `subtitle`  |
| `publisher`                    | Publisher                  |
| `year`                         | Publish Year               |
| `composer`                     | Extra property `narrators` |
| `description`                  | Description                |
| `genre`                        | Tags\*\*                   |
| `series` / `mvnm`              | Series                     |
| `series-part` / `mvin`         | Volume                     |
| `language` / `lang`            | Language                   |
| `isbn`                         | Identifiers `isbn`         |
| `asin` / `audible_asin`        | Identifiers `asin`         |
| Overdrive MediaMarkers         | Extra property `chapters`  |

-   \* Authors naming as well as multiple authors separated by `,`, `;`, `&` or `and`.
-   \*\* Tags can include multiple tags separated by `/`, `//`, or `;`. e.g. "Science Fiction/Fiction/Fantasy"

You can find all metadata into `getExtras()` array of `Ebook::class`.

#### EPUB

With `EPUB`, metadata are extracted from `OPF` file, `META-INF/container.xml` files, you could access to these metatada but you can also get chapters from `NCX` file. And with `chapters()` method you can merge `NCX` and `HTML` chapters to get full book chapters with `label`, `source` and `content`.

```php
use Kiwilan\Ebook\Ebook;

$ebook = Ebook::read('path/to/ebook.epub');

$epub = $ebook->getParser()?->getEpub();

$epub->getContainer(); // ?EpubContainer => {`opfPath`: ?string, `version`: ?string, `xml`: array}
$epub->getOpf(); // ?OpfItem => {`metadata`: array, `manifest`: array, `spine`: array, `guide`: array, `epubVersion`: ?int, `filename`: ?string, `dcTitle`: ?string, `dcCreators`: BookAuthor[], `dcContributors`: BookContributor[], `dcDescription`: ?string, `dcPublisher`: ?string, `dcIdentifiers`: BookIdentifier[], `dcDate`: ?DateTime, `dcSubject`: string[], `dcLanguage`: ?string, `dcRights`: array, `meta`: BookMeta[], `coverPath`: ?string, `contentFile`: string[]}
$epub->getNcx(); // ?NcxItem => {`head`: NcxItemHead[]|null, `docTitle`: ?string, `navPoints`: NcxItemNavPoint[]|null, `version`: ?string, `lang`: ?string}
$epub->getChapters(); // EpubChapter[] => {`label`: string, `source`: string, `content`: string}[]
$epub->getHtml(); // EpubHtml[] => {`filename`: string, `head`: ?string, `body`: ?string}[]
$epub->getFiles(); // string[] => all files in EPUB
```

> [!NOTE]
>
> For performance reasons, with `ncx`, `html` and `chapters` are only available on demand. If you use `var_dump` to check metadata, these properties will be `null`.

### Creation

You can create an EPUB or CBZ file with `create()` static method.

> [!NOTE]
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

|       Name       |               Extensions                | Supported |                                                   Uses                                                   |                                Support cover                                | Support series |
| :--------------: | :-------------------------------------: | :-------: | :------------------------------------------------------------------------------------------------------: | :-------------------------------------------------------------------------: | :------------: |
|   EPUB (IDPF)    |                 `.epub`                 |    ✅     |                        Native [`zip`](https://www.php.net/manual/en/book.zip.php)                        |                                     ✅                                      |       ✅       |
| Kindle (Amazon)  |     `.azw`, `.azw3`, `.kf8`, `.kfx`     |    ✅     |                 Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php)                 |                ✅ (See [MOBI cover note](#mobi-cover-note))                 |       ❌       |
|    Mobipocket    |             `.mobi`, `.prc`             |    ✅     |                 Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php)                 |                ✅ (See [MOBI cover note](#mobi-cover-note))                 |       ❌       |
|       PDF        |                 `.pdf`                  |    ✅     |                   [`smalot/pdfparser`](https://github.com/smalot/pdfparser) (included)                   |      Uses [`imagick`](https://www.php.net/manual/en/book.imagick.php)       |       ❌       |
|  iBook (Apple)   |                `.ibooks`                |    ❌     |                                                                                                          |                                     N/A                                     |      N/A       |
|       DjVu       |             `.djvu`, `.djv`             |    ❌     |                                                                                                          |                                     N/A                                     |      N/A       |
| Rich Text Format |                 `.rtf`                  |    ❌     |                                                                                                          |                                     N/A                                     |      N/A       |
|   FictionBook    |                 `.fb2`                  |    ✅     |                 Native [`filesystem`](https://www.php.net/manual/en/book.filesystem.php)                 |                                     ✅                                      |       ✅       |
| Broadband eBooks |             `.lrf`, `.lrx`              |    ❌     |                                                                                                          |                                     N/A                                     |      N/A       |
|    Palm Media    |                 `.pdb`                  |    ❌     |                                                                                                          |                                     N/A                                     |      N/A       |
|    Comics CBZ    |                 `.cbz`                  |    ✅     |                        Native [`zip`](https://www.php.net/manual/en/book.zip.php)                        |                                     ✅                                      |       ✅       |
|    Comics CBR    |                 `.cbr`                  |    ✅     | [`rar`](https://github.com/cataphract/php-rar) PHP extension or [`p7zip`](https://www.7-zip.org/) binary |                                     ✅                                      |       ✅       |
|    Comics CB7    |                 `.cb7`                  |    ✅     |                                 [`p7zip`](https://www.7-zip.org/) binary                                 |                                     ✅                                      |       ✅       |
|    Comics CBT    |                 `.cbt`                  |    ✅     |                       Native [`phar`](https://www.php.net/manual/en/book.phar.php)                       |                                     ✅                                      |       ✅       |
|      Audio       | `.mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg` |    ✅     |               If [`kiwilan/php-audio`](https://github.com/kiwilan/php-audio) is installed                | [Depends of format](https://github.com/kiwilan/php-audio#supported-formats) |       ❌       |

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
-   [Ewilan Rivière](https://github.com/ewilan-riviere) author of this package
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-ebook.svg?style=flat&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/php-ebook
[php-version-src]: https://img.shields.io/static/v1?style=flat&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/php-ebook.svg?style=flat&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/php-ebook
[license-src]: https://img.shields.io/github/license/kiwilan/php-ebook.svg?style=flat&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/php-ebook/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/php-ebook/run-tests.yml?branch=main&label=tests&style=flat&colorA=18181B
[tests-href]: https://packagist.org/packages/kiwilan/php-ebook
[codecov-src]: https://img.shields.io/codecov/c/gh/kiwilan/php-ebook/main?style=flat&colorA=18181B&colorB=777BB4
[codecov-href]: https://codecov.io/gh/kiwilan/php-ebook
