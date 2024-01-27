# Changelog

All notable changes to `php-ebook` will be documented in this file.

## v2.3.12 - 2024-01-27

In `MetaTitle`, add series before title for `getSlug()`.

## v2.3.11 - 2024-01-27

In `MetaTitle`, move year after author.

## v2.3.1 - 2024-01-27

Deprecated some `MetaTitle` methods `getSlugSort()`, `getSlugUnique()`, `getSerieSlug()`, `getSerieSlugSort()`, `getSerieSlugUnique()`, `getSlugSortWithSerie()`, `getUniqueFilename()`. Now only `getSlug()`, `getSlugSimple()`, `getSeriesSlug()`, `getSeriesSlugSimple()` are available.

Slug have to be unique, so default slug take some metadata to be unique, like in this example:

An EPUB with title `La pâle lumière des ténèbres` with main author `Pierre Bottero`, series `A comme Association`, volume `1`, language `fr` and published in `1980` will have the slug `pale-lumiere-des-tenebres-a-comme-association-01-1980-pierre-bottero-epub-fr`.

You can use `getSlugSimple()` to have a simple slug, like `pale-lumiere-des-tenebres`.

For series, you can use `getSeriesSlug()` to have a slug with series name, like `a-comme-association-1980-pierre-bottero-epub-fr`.

And `getSeriesSlugSimple()` to have a simple slug with series name, like `a-comme-association`.

## v2.3.0 - 2024-01-22

Drop `kiwilan/php-audio` dependency.

Audiobooks are cool, but I suppose many users don't need it. So I think it's better to drop `kiwilan/php-audio` dependency, you have to install `kiwilan/php-audio` manually.
If you scan audiobooks without `kiwilan/php-audio` installed, you will get an error message.

## v2.2.01 - 2023-12-06

Update dependencies (drop symfony/process dependency)

- `EbookMetadata` is now `EbookParser` for consistency
  
  - Method `getMetadata` is now `getParser`
  - Method `hasMetadata` is now `hasParser`
  
- `BookIdentifier` can use now `autoDetect` to detect identifier `scheme` from `value`, you can disable this feature by passing `false` as third argument
  

## v2.2.0 - 2023-09-24

### Breaking changes

- All `Metadata` internal classes have been renamed to `Module`
- Some internal parser classes, have been moved into `Parser` namespace
- All `getContent()` methods have been renamed to `getContents()` (old methods are deprecated)

### New features

- MOBI and derivatives support (`.azw`, `.azw3`, `.kf8`, `.kfx`, `.mobi`, `.prc`) with cover
- FB2 support (`.fb2`) with cover and series
- Improve BookIdentifier scheme detection

### Misc

- Fixing EPUB volume `0` issue
- Add more tests with samples
- Update dependencies
- Improve documentation

## v2.1.02 - 2023-08-30

- Better handle of PDF, parser works even if metadata are not present
  - Add more tests on PDF, update `kiwilan/php-archive` with patch for PDF
  - Thanks to @SergioMendolia for PR [https://github.com/kiwilan/php-ebook/pull/48](https://github.com/kiwilan/php-ebook/pull/48)
  

## v2.1.01 - 2023-08-29

- avoids crashing when trying to read metadata while archive is null, by @SergioMendolia in [https://github.com/kiwilan/php-ebook/pull/45](https://github.com/kiwilan/php-ebook/pull/45)

## v2.1.0 - 2023-08-28

- Add some improvements for `description` parsing (remove extra spaces, remove newlines, etc.)
- Add `EbookCreator::class` with `create()` method into `Ebook::class` that allows to create ebook from `Ebook` instance
  - Some methods allow to set content to ebook: `addFromString()`, `addFile()`, `addDirectory()`
  

## v2.0.20 - 2023-08-28

- add `descriptionHtml()` method to `Ebook::class`, which can contains description with html tags if it is available, html is sanitized, original description is still available via `description()` method with plain text
- add `getBasename()` method to `Ebook::class`, which returns basename of ebook file, `getFilename()` now return real filename of ebook file
- add `isValid(string $path)` static method to `Ebook::class`, which checks if ebook file is valid, thanks to @SergioMendolia: [https://github.com/kiwilan/php-ebook/issues/38](https://github.com/kiwilan/php-ebook/issues/38)
- fix `<dc:creator>` empty tag in `opf` file, thanks to @SergioMendolia: [https://github.com/kiwilan/php-ebook/pull/39](https://github.com/kiwilan/php-ebook/pull/39)
- Bugfixes

## 2.0.12 - 2023-08-10

- fix `OpfMetadata` `dcRights()` parse array to string

## 2.0.11 - 2023-08-10

- add `Epub` property `isBadFile` to check if the file is corrupted, eBook file will be read but not parsed if it is corrupted, it's possible to know if file is valid with `isBadFile()` method or with `hasMetadata()` method
- fix some problems with OPF parsing to be more flexible

## 2.0.1 - 2023-08-10

- add `opf:metadata` support for OPF files

## 2.0.0 - 2023-08-08

### BREAKING CHANGES

- All simple getters have now `get` prefix. For example, `getTitle()` instead of `title()`, `getPublisher()` instead of `publisher()`, etc. It concerns all simple getters of `EpubMetadata`, `EpubChapter`, `EpubHtml`, `NcxMetadata`, `EpubContainer`, `OpfMetadata`, `Ebook`, `BookIdentifier`, `EbookCover`, `EbookMetadata`, `AudiobookMetadata`, `BookAuthor`, `CbaMetadata`, `CbamMetadata`, `MetaTitle` classes.

> Why?
All these classes have some methods like setters or actions. To be consistent and clear, all simple getters have now `get` prefix.

### Bugfixes

- `BookContributor` and `BookIdentifier` can be more flexible with `mixed` types

## 1.3.54 - 2023-07-23

- EbookCover `content` method fix

## 1.3.53 - 2023-07-07

- bump `kiwilan/php-archive` `1.5.12`

## 1.3.52 - 2023-07-07

- bump `kiwilan/php-archive` to `1.5.11`

## 1.3.51 - 2023-07-06

- bumpp `kiwilan/php-archive`

## 1.3.41 - 2023-06-28

- `chore`: `kiwilan/php-xml-reader` to `0.2.31`

## 1.3.4 - 2023-06-28

- BREAKING CHANGE: `extrasExtract()` is now `extra()`
- `chore`: `kiwilan/php-xml-reader` to 0.2.30

## 1.3.35 - 2023-06-27

- `chore`: update `kiwilan/php-xml-reader` to `0.2.22`

## 1.3.34 - 2023-06-22

- `MetaTitle` improve `uniqueFilename`

## 1.3.33 - 2023-06-22

- `MetaTitle` fix `uniqueFilename()` with series

## 1.3.32 - 2023-06-22

- limit length fix bug

## 1.3.31 - 2023-06-22

- improve `toArray()` for `EpubMetadata`
- add `limit` option to `copyright` and `description`

## 1.3.30 - 2023-06-20

- Fix some `kiwilan/php-xml-reader` parsing error

## 1.3.20 - 2023-06-20

- Update dependency `kiwilan/php-xml-reader`

## 1.3.10 - 2023-06-19

- `OpfMetadata::class` fix only one tag

## 1.3.0 - 2023-06-19

- Use `kiwilan/php-xml-reader` instead of `XmlReader::class`

## 1.2.0 - 2023-06-16

**BREAKING CHANGE**

- `BookIdentifier::class` => `content` is now `value` and `type` is now `scheme`

**FEATURE**

- Add `EPUB` chapters support

## 1.1.0 - 2023-06-16

- Add partial `.mobi` support
- For `EbookMetadata::class` all methods with `has` prefix use `is` prefix now
- Add `uniqueFilename` method to `MetaTitle::class`
- improve documentation
- add tests

## 1.0.12 - 2023-06-15

- `MetaTitle` add `uniqueFilename`

## 1.0.11 - 2023-06-14

- `OpfMetadata` fix cover parser to keep only pictures

## 1.0.10 - 2023-06-14

- `EpubMetadata::class` fix `parseFiles` method

## 1.0.01 - 2023-06-14

- add `extrasExtract` method in `Ebook::class`

## 1.0.0 - 2023-06-13

**BREAKING CHANGES**

- `book` property has been removed, all book metadata are now on root of `Ebook::class`
- `metadata` property is now `EbookMetadata::class` with `module` property and `epub`, `cba`, `pdf`, `audiobook` properties to parse metadata
- count for pages or words is now available if `wordsCount` or `pagesCount` is called

**Features**

- improve performances
- add audiobooks

## 0.34.0 - 2023-05-25

- BookIdentifier `content` is now nullable

## 0.33.0 - 2023-05-25

- BookMeta `name` and `content` are now nullable

## 0.32.0 - 2023-05-25

- Add no metadata case with `title` as filename for `epub`

## 0.31.0 - 2023-05-08

- `BookEntity` property `words` is now `wordsCount`

## 0.3.40 - 2023-05-08

- `EpubOpf` is now `OpfMetadata`, `CbaFormat` is now `CbaMetadata`
- add properties to `OpfMetadata` with `metadata`, `manifest`, `spine`, `guide`
- `MetaTitle` fix accent characters
- add tests

## 0.3.32 - 2023-05-08

- MetaTitle `slugSortWithSerie` fix

## 0.3.31 - 2023-05-05

- improve documentation

## 0.3.30 - 2023-05-05

- make `slugSortWithSerie` always generated (if title)

## 0.3.20 - 2023-05-05

- update `titleMeta` to `metaTitle`

## 0.3.10 - 2023-05-05

- add `titleMeta()` to `BookEntity` with extra infos with title slug and series slug
- move `ComicMeta` to `Kiwilan\Ebook\Entity\ComicMeta`

## 0.3.0 - 2023-05-05

- add `filename` to `Ebook`
- remove `path` from `BookEntity` (it's in `Ebook`)
- `Book` `manga` is default `UNKNOWN`
- add methods `toArray`, `toJson` and `__toString` to `Ebook`, `BookEntity`, `OpfMetadata`, `CbaMetadata`

## 0.2.10 - 2023-05-05

- add `words` property

## 0.2.0 - 2023-05-05

- Move `BookEntity` `cover` to `Ebook`

## 0.1.01 - 2023-05-05

- Update `kiwilan/php-archive`

## 0.1.0 - 2023-04-01

init
