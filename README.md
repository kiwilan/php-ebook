# PHP eBook

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read metadata and extract covers from eBooks (`.epub`, `.cbz`, `.cbr`, `.cb7`, `.cbt`, `.pdf`) and audiobooks (`mp3`, `.m4a`, `.m4b`, `.flac`, `.ogg`).

Supports Linux, macOS and Windows.

> **Warning**
>
> Works with [kiwilan/php-archive](https://github.com/kiwilan/php-archive), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [p7zip](https://www.7-zip.org/) binary could be necessary, see [Requirements](#requirements).

<p align="center">
  <img src="tests/media/banner.jpg" style="width: 80%;" alt="Banner with eReader picture in background and PHP eBook title" />
</p>

## Table of Contents

-   [About](#about)
-   [Requirements](#requirements)
-   [Features](#features)
-   [Installation](#installation)
-   [Usage](#usage)
    -   [Metadata](#metadata)
    -   [MetaTitle](#metatitle)
    -   [EPUB](#epub)
-   [Roadmap](#roadmap)
-   [Testing](#testing)
-   [Changelog](#changelog)
-   [Resources](#resources)
    -   [OS](#os)
    -   [Applications](#applications)
    -   [Tools](#tools)
    -   [Metadata](#metadata-1)
    -   [OPDS](#opds)
-   [Credits](#credits)
-   [License](#license)

## About

This package was built for [bookshelves-project/bookshelves](https://github.com/bookshelves-project/bookshelves), a web app to handle eBooks.

## Requirements

-   **PHP version** >= _8.1_
-   **PHP extensions**:
    -   [`intl`](https://www.php.net/manual/en/book.intl.php) (native) for `Transliterator`
    -   [`zip`](https://www.php.net/manual/en/book.zip.php) (native, optional) for `.EPUB`, `.CBZ`
    -   [`fileinfo`](https://www.php.net/manual/en/book.fileinfo.php) (native, optional) for better detection of file type
    -   [`rar`](https://www.php.net/manual/en/book.rar.php) (optional) for `.CBR`
    -   [`imagick`](https://www.php.net/manual/en/book.imagick.php) (optional) for `.PDF`

|                Type                | Supported |                                               Requirement                                                |         Uses         |
| :--------------------------------: | :-------: | :------------------------------------------------------------------------------------------------------: | :------------------: |
|          `.epub`, `.cbz`           |    ✅     |                                                   N/A                                                    |         N/A          |
|               `.cbt`               |    ✅     |                                                   N/A                                                    |         N/A          |
|               `.cbr`               |    ✅     | [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary | PHP `rar` or `p7zip` |
|               `.cb7`               |    ✅     |                                 [`p7zip`](https://www.7-zip.org/) binary                                 |    `p7zip` binary    |
|               `.pdf`               |    ✅     |         Optional (for extraction) [`imagick` PHP extension](https://github.com/Imagick/imagick)          |  `smalot/pdfparser`  |
| `mp3`, `m4a`, `m4b`, `flac`, `ogg` |    ✅     |                                                   N/A                                                    | `kiwilan/php-audio`  |

If you want more informations, you can read [`kiwilan/php-archive`](https://github.com/kiwilan/php-archive#requirements).

## Features

-   Read metadata from eBooks and audiobooks
-   Extract covers from eBooks and audiobooks
-   Support metadata
    -   `EPUB` v2 and v3 from [IDPF](https://idpf.org/)
    -   `calibre:series` for EPUB from [Calibre](https://calibre-ebook.com/)
    -   `calibre:series_index` for EPUB from [Calibre](https://calibre-ebook.com/)
    -   `ComicInfo.xml` (CBAM) format from ComicRack and maintained by [anansi-project](https://github.com/anansi-project/comicinfo)
    -   `PDF` by [smalot/pdfparser](https://github.com/smalot/pdfparser)
    -   `ID3`, Vorbis and flac tags with [kiwilan/php-audio](https://github.com/kiwilan/php-audio)

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-ebook
```

## Usage

With eBook files (`.epub`, `.cbz`, `.cba`, `.cbr`, `.cb7`, `.cbt`, `.pdf`) or audiobook files (`mp3`, `m4a`, `m4b`, `flac`, `ogg`).

```php
$ebook = Ebook::read('path/to/archive.epub');

// File data
$ebook->path(); // string
$ebook->filename(); // string
$ebook->extension(); // string

// Book data
$ebook->title(); // string
$ebook->metaTitle(); // ?MetaTitle, with slug and sort properties for `title` and `series`
$ebook->authors(); // BookAuthor[] (name: string, role: string)
$ebook->authorMain(); // ?BookAuthor => First BookAuthor (name: string, role: string)
$ebook->description(); // ?string
$ebook->copyright(); // ?string
$ebook->publisher(); // ?string
$ebook->identifiers(); // BookIdentifier[] (content: string, type: string)
$ebook->publishDate(); // ?DateTime
$ebook->language(); // ?string
$ebook->tags(); // string[] => `subject` in EPUB, `keywords` in PDF, `genres` in CBA
$ebook->series(); // ?string => `calibre:series` in EPUB, `series` in CBA
$ebook->volume(); // ?int => `calibre:series_index` in EPUB, `number` in CBA
$ebook->pagesCount(); // ?int => computed from words (250 words by page) in EPUB, `pageCount` in PDF, `pageCount` in CBA
$ebook->wordsCount(); // ?int => words count in EPUB

// Additional data
$ebook->format(); // ?EbookFormatEnum => `epub`, `pdf`, `cba`
$ebook->cover(); // ?EbookCover => cover of book
$ebook->extras(); // array => additional data for book
$ebook->extrasExtract(string $key); // mixed => safely extract data from `extras` array

// Check validity
$ebook->isArchive(); // bool
$ebook->isAudio(); // bool
$ebook->hasMetadata(); // bool
$ebook->hasCover(); // bool
```

### Metadata

```php
$ebook = Ebook::read('path/to/archive.epub');

$metadata = $ebook->metadata(); // with `module` as `EbookModule::class`, can be `EpubMetadata::class`, `PdfMetadata::class`, `CbaMetadata::class` or `AudiobookMetadata::class`

$metadata->epub(); // `EpubMetadata::class`
$metadata->pdf(); // `PdfMetadata::class`
$metadata->cba(); // `CbaMetadata::class`
$metadata->audiobook(); // `AudiobookMetadata::class`
```

### MetaTitle

Can be set if book's title is not null.

```php
$ebook = Ebook::read('path/to/archive.epub');
$metaTitle = $ebook->metaTitle(); // ?MetaTitle

$metaTitle->slug(); // string => slugify title, like `the-clan-of-the-cave-bear`
$metaTitle->slugSort(); // string => slugify title without determiners, like `clan-of-the-cave-bear`
$metaTitle->slugLang(); // string => slugify title with language and type, like `the-clan-of-the-cave-bear-epub-en`

$metaTitle->serieSlug(); // ?string => slugify series title, like `earths-children`
$metaTitle->serieSort(); // ?string => slugify series title without determiners, like `earths-children`
$metaTitle->serieLang(); // ?string => slugify series title with language and type, like `earths-children-epub-en`

$metaTitle->slugSortWithSerie(); // string => slugify title with series title and volume, like `earths-children-01_clan-of-the-cave-bear`
$metaTitle->uniqueFilename(); // string => unique filename for storage, like `jean-m-auel-earths-children-01-clan-of-the-cave-bear-en-epub`
```

### EPUB

#### Authors

Good multiple creators: `Terry Pratchett & Stephen Baxter`.

```php
[
    [
        "@content" => "Terry Pratchett"
        "@attributes" => [
            "role" => "aut"
            "file-as" => "Pratchett, Terry & Baxter, Stephen"
        ]
    ],
    [
        "@content" => "Stephen Baxter"
        "@attributes" => array:1 [
            "role" => "aut"
        ]
    ]
]
```

Bad multiple creators: `Jean M. Auel, Philippe Rouard`.

```php
[
    "@content" => "Jean M. Auel, Philippe Rouard"
    "@attributes" => array:2 [
        "role" => "aut"
        "file-as" => "Jean M. Auel, Philippe Rouard"
    ]
]
```

## Roadmap

-   [ ] Add `.mobi`, `.azw`, `.azw3` support
    -   https://stackoverflow.com/questions/11817047/php-library-to-parse-mobi
    -   https://wiki.mobileread.com/wiki/MOBI
-   [ ] Add `.djvu` support
-   [ ] Add `.fb2`, `.lrf`, `.pdb`, `.snb` support
-   [ ] Add `.epub` creation support
-   [ ] Add `.epub` metadata update support

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Resources

### OS

-   [koreader/koreader](https://github.com/koreader/koreader): an ebook reader application supporting many more formats, running on ereaders and Android devices
-   [baskerville/plato](https://github.com/baskerville/plato): document reader

### Applications

#### Desktop

-   [kovidgoyal/calibre](https://github.com/kovidgoyal/calibre): calibre ebook manager
-   [troyeguo/koodo-reader](https://github.com/troyeguo/koodo-reader): modern ebook manager and reader with sync and backup capacities for Windows, macOS, Linux and Web
-   [thorium](https://thorium.edrlab.org/): EPUB reader for Windows, MacOS and Linux (support OPDS)

#### Server

-   [greizgh/bookshelf](https://gitlab.com/greizgh/bookshelf): lightweight epub online catalog (support OPDS)
    -   [Bookshelf: publier ses ebooks simplement](https://linuxfr.org/users/grzgh/journaux/bookshelf-publier-ses-ebooks-simplement): usage of `greizgh/bookshelf` (french)
-   [Kareadita/Kavita](https://github.com/Kareadita/Kavita): fast, feature rich, cross platform reading server, built with a focus for manga (support OPDS)
-   [gotson/komga](https://github.com/gotson/komga): media server for comics/mangas/BDs with API (support OPDS)
-   [advplyr/audiobookshelf](https://github.com/advplyr/audiobookshelf): self-hosted audiobook and podcast server
-   [bookshelves-project](https://github.com/bookshelves-project): web application to manage eBooks (support OPDS)
-   [seblucas/cops](https://github.com/seblucas/cops): Calibre OPDS PHP Server, web-based light alternative to Calibre content server (support OPDS)
-   [janeczku/calibre-web](https://github.com/janeczku/calibre-web): Web app for browsing, reading and downloading eBooks stored in a Calibre database

### Tools

-   [framabook/systeme-publication-framabook](https://framagit.org/framabook/systeme-publication-framabook): with pandoc, provides the basics for deploying an accessibility compliant epub production system using only command line tools in a Bash shell terminal. (french)
-   [pandoc.org](https://pandoc.org/epub.html): creating an ebook with pandoc
-   [dino-/epub-tools](https://github.com/dino-/epub-tools): command line utilities for working with epub files
-   [comictagger/comictagger](https://github.com/comictagger/comictagger): a multi-platform app for writing metadata to digital comics

### Metadata

-   [IDPF/epub3-samples](https://github.com/IDPF/epub3-samples): EPUB 3 Sample Documents
-   [anansi-project](https://github.com/anansi-project): initiative to bring structure and cohesion to the world of metadata for Comic Books, Mangas, and other graphic novels.
-   Comic Book Archive (CBA) metadata
    -   Comic Book Archive Metadata (CBAM) / ComicRack Metadata (CRM)
    -   Comic Book Markup Language (CBML)
-   [comictagger/wiki/MetadataSchemes](https://github.com/comictagger/comictagger/wiki/MetadataSchemes): all the details [...] on various open metadata schemes for comic archives
-   [comicvine](https://comicvine.gamespot.com): the largest comic book wiki in the universe
-   [w3.org](https://www.w3.org/publishing/epub3/epub-spec.html): EPUB specs by W3C
-   [opds.io](https://specs.opds.io/): OPDS specs

### OPDS

> The Open Publication Distribution System (OPDS) is an application of the Atom Syndication Format intended to enable content creators and distributors to distribute digital books via a simple catalog format. This format is designed to work interchangeably across multiple desktop and device software programs.
> From [mobileread](https://wiki.mobileread.com/wiki/OPDS)

-   [atramenta.net](https://www.atramenta.net/)
-   [ebooksgratuits.com](https://www.ebooksgratuits.com/) (french)
-   [gallica.bnf.fr](https://gallica.bnf.fr/accueil/en/content/accueil-en?mode=desktop)
-   [wikisource.org](https://en.wikisource.org/wiki/Main_Page)
-   [bibebook.com](http://www.bibebook.com/) (french)
-   [standardebooks.org](https://standardebooks.org/)
-   [gutenberg.org](https://gutenberg.org/)
-   [feedbooks.com](https://www.feedbooks.com/)

## Credits

-   [Kiwilan](https://github.com/kiwilan)
-   [spatie](https://github.com/spatie) for `spatie/package-skeleton-php`
-   [kiwilan/php-audio](https://github.com/kiwilan/php-audio)

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
