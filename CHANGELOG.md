# Changelog

All notable changes to `php-ebook` will be documented in this file.

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
