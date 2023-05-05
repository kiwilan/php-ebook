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
> Works with [kiwilan/php-archive](https://github.com/kiwilan/php-archive), for some formats (`.cbr` and `.cb7`) [`rar` PHP extension](https://github.com/cataphract/php-rar) or [p7zip](https://www.7-zip.org/) binary could be necessary, see [Requirements](#requirements).

## About

This package was built for [bookshelves-project/bookshelves-back](https://github.com/bookshelves-project/bookshelves-back), a web app to handle eBooks.

## Requirements

-   PHP >= 8.1
-   Depends of extension and features you want to use

|      Type       | Native |                                                                                       Dependency                                                                                       |
| :-------------: | :----: | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| `.epub`, `.cbz` |   ✅   |                                                                                          N/A                                                                                           |
|     `.cbt`      |   ✅   |                                                                                          N/A                                                                                           |
|     `.cbr`      |   ❌   |                                        [`rar` PHP extension](https://github.com/cataphract/php-rar) or [`p7zip`](https://www.7-zip.org/) binary                                        |
|     `.cb7`      |   ❌   |                                                                        [`p7zip`](https://www.7-zip.org/) binary                                                                        |
|     `.pdf`      |   ✅   |                                                Optional (for extraction) [`imagick` PHP extension](https://github.com/Imagick/imagick)                                                 |
|       ALL       |   ❌   | [`p7zip`](https://www.7-zip.org/) binary ([`rar` PHP extension](https://github.com/cataphract/php-rar) and [`imagick` PHP extension](https://github.com/Imagick/imagick) are optional) |

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

$metadata = $ebook->metadata(); // EpubOpf|CbaFormat|null => metadata OPF for EPUB, metadata CBA for CBA
$format = $book->format(); // epub, pdf, cba
$book = $ebook->book(); // BookEntity
$cover = $ebook->cover(bool $convertBase64 = true); // string => cover as string ($toString convert base64)
$path = $ebook->path(); // string
$filename = $ebook->filename(); // string
$extension = $ebook->extension(); // string
$hasMetadata = $ebook->hasMetadata(); // bool
```

### Book

```php
$book = $ebook->book(); // BookEntity

$book->title(); // string
$book->metaTitle(); // MetaTitle, with `slug` and `sort` properties for `title` and `series`
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
$book->words(); // int => `words` count in EPUB
$book->editors(); // string[] => `editors` in CBA
$book->review(); // string => `review` in CBA
$book->web(); // string => `web` in CBA
$book->manga(); // MangaEnum => `manga` in CBA | Addtional data about mangas
$book->isBlackAndWhite(); // bool => `blackAndWhite` in CBA
$book->ageRating(); // AgeRatingEnum => `ageRating` in CBA | Addtional data about age rating
$book->comicMeta(); // ComicMeta => Addtional data for CBA
```

### MetaTitle

```php
$metaTitle = $book->metaTitle(); // MetaTitle

$metaTitle->slug(); // string => slugify title
$metaTitle->slugSort(); // string => slugify title without determiners
$metaTitle->slugLang(); // string => slugify title with language

$metaTitle->serieSlug(); // string => slugify series title
$metaTitle->serieSort(); // string => slugify series title without determiners
$metaTitle->serieLang(); // string => slugify series title with language

$metaTitle->slugSortWithSerie(); // string => slugify title with series title and volume
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

## Resources

### OS

-   [koreader/koreader](https://github.com/koreader/koreader): an ebook reader application supporting many more formats, running on ereaders and Android devices
-   [baskerville/plato](https://github.com/baskerville/plato): document reader

### Applications

#### Desktop

-   [kovidgoyal/calibre](https://github.com/kovidgoyal/calibre): calibre ebook manager
-   [troyeguo/koodo-reader](https://github.com/troyeguo/koodo-reader): modern ebook manager and reader with sync and backup capacities for Windows, macOS, Linux and Web
-   [thorium](https://thorium.edrlab.org/): EPUB reader for Windows, MacOS and Linux (support OPDS)
-   [Kareadita/Kavita](https://github.com/Kareadita/Kavita): fast, feature rich, cross platform reading server, built with a focus for manga (support OPDS)

#### Server

-   [greizgh/bookshelf](https://gitlab.com/greizgh/bookshelf): lightweight epub online catalog (support OPDS)
    -   [Bookshelf: publier ses ebooks simplement](https://linuxfr.org/users/grzgh/journaux/bookshelf-publier-ses-ebooks-simplement): usage of `greizgh/bookshelf` (french)
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
