# Changelog

All notable changes to `php-ebook` will be documented in this file.

## 0.3.10 - 2023-05-05

- add `titleMeta()` to `BookEntity` with extra infos with title slug and series slug
- move `ComicMeta` to `Kiwilan\Ebook\Entity\ComicMeta`

## 0.3.0 - 2023-05-05

- add `filename` to `Ebook`
- remove `path` from `BookEntity` (it's in `Ebook`)
- `Book` `manga` is default `UNKNOWN`
- add methods `toArray`, `toJson` and `__toString` to `Ebook`, `BookEntity`, `EpubOpf`, `CbaFormat`

## 0.2.10 - 2023-05-05

- add `words` property

## 0.2.0 - 2023-05-05

- Move `BookEntity` `cover` to `Ebook`

## 0.1.01 - 2023-05-05

- Update `kiwilan/php-archive`

## 0.1.0 - 2023-04-01

init
