## Table of Contents

-   [Formats](#formats)
-   [Tools](#tools)
-   [Resources](#resources)
    -   [OS](#os)
    -   [Applications](#applications)
    -   [Metadata](#metadata)
    -   [OPDS](#opds)
-   [Troubles](#troubles)
    -   [EPUB](#epub)

## Formats

-   [`EPUB`](https://wiki.mobileread.com/wiki/EPub)
-   [`MOBI`](https://wiki.mobileread.com/wiki/MOBI)
-   [`AZW`](https://wiki.mobileread.com/wiki/AZW)

## Tools

-   [`exiftool`](https://github.com/exiftool/exiftool)
-   [`ebook-meta`](https://manual.calibre-ebook.com/generated/en/ebook-meta.html)
-   [`dino-/epub-tools`](https://github.com/dino-/epub-tools): command line utilities for working with epub files
-   [`libmobi`](https://github.com/bfabiszewski/libmobi)
-   [ebook-tools](https://github.com/na--/ebook-tools)
-   [`framabook/systeme-publication-framabook`](https://framagit.org/framabook/systeme-publication-framabook): with pandoc, provides the basics for deploying an accessibility compliant epub production system using only command line tools in a Bash shell terminal. (french)
-   [pandoc.org](https://pandoc.org/epub.html): creating an ebook with pandoc
-   [`comictagger/comictagger`](https://github.com/comictagger/comictagger): a multi-platform app for writing metadata to digital comics

## Resources

### OS

-   [`koreader/koreader`](https://github.com/koreader/koreader): an ebook reader application supporting many more formats, running on ereaders and Android devices
-   [`baskerville/plato`](https://github.com/baskerville/plato): document reader

### Applications

#### Desktop

-   [`kovidgoyal/calibre`](https://github.com/kovidgoyal/calibre): calibre ebook manager
-   [`troyeguo/koodo-reader`](https://github.com/troyeguo/koodo-reader): modern ebook manager and reader with sync and backup capacities for Windows, macOS, Linux and Web
-   [`thorium`](https://thorium.edrlab.org/): EPUB reader for Windows, MacOS and Linux (support OPDS)

#### Server

-   [`greizgh/bookshelf`](https://gitlab.com/greizgh/bookshelf): lightweight epub online catalog (support OPDS)
    -   [Bookshelf: publier ses ebooks simplement](https://linuxfr.org/users/grzgh/journaux/bookshelf-publier-ses-ebooks-simplement): usage of `greizgh/bookshelf` (french)
-   [`Kareadita/Kavita`](https://github.com/Kareadita/Kavita): fast, feature rich, cross platform reading server, built with a focus for manga (support OPDS)
-   [`gotson/komga`](https://github.com/gotson/komga): media server for comics/mangas/BDs with API (support OPDS)
-   [`advplyr/audiobookshelf`](https://github.com/advplyr/audiobookshelf): self-hosted audiobook and podcast server
-   [`bookshelves`](https://github.com/bookshelves-project): web application to manage eBooks (support OPDS)
-   [`seblucas/cops`](https://github.com/seblucas/cops): Calibre OPDS PHP Server, web-based light alternative to Calibre content server (support OPDS)
-   [`janeczku/calibre-web`](https://github.com/janeczku/calibre-web): Web app for browsing, reading and downloading eBooks stored in a Calibre database

### Metadata

-   [`IDPF/epub3-samples`](https://github.com/IDPF/epub3-samples): EPUB 3 Sample Documents
-   [`anansi-project`](https://github.com/anansi-project): initiative to bring structure and cohesion to the world of metadata for Comic Books, Mangas, and other graphic novels.
-   Comic Book Archive (CBA) metadata
    -   Comic Book Archive Metadata (CBAM) / ComicRack Metadata (CRM)
    -   Comic Book Markup Language (CBML)
-   [`comictagger/wiki/MetadataSchemes`](https://github.com/comictagger/comictagger/wiki/MetadataSchemes): all the details [...] on various open metadata schemes for comic archives
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

## Troubles

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
