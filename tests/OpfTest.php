<?php

use Kiwilan\Ebook\Formats\Epub\Parser\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\Parser\OpfItem;
use Kiwilan\Ebook\Models\BookMeta;
use Kiwilan\XmlReader\XmlReader;

it('can parse epub container', function (string $path) {
    $container = EpubContainer::make(file_get_contents($path));

    expect($path)->toBeReadableFile();
    expect($container->getOpfPath())->toBeString();
    expect($container->getVersion())->toBeString();
})->with([EPUB_CONTAINER_EPUB2, EPUB_CONTAINER_EPUB3]);

it('can failed if bad file', function () {
    expect(fn () => EpubContainer::make(file_get_contents(EPUB_CONTAINER_EPUB2_BAD)))->toThrow(Exception::class);
});

it('can failed if empty file', function () {
    expect(fn () => EpubContainer::make(file_get_contents(EPUB_CONTAINER_EPUB2_EMPTY)))->toThrow(Exception::class);
});

it('can failed with wrong XML', function () {
    expect(fn () => XmlReader::make('<html><body><body></html>'))->toThrow(Exception::class);
});

it('can parse epub opf', function (string $path) {
    $opf = OpfItem::make(file_get_contents($path), $path);

    expect($opf)->tobeInstanceOf(OpfItem::class);
    expect($path)->toBeReadableFile();
    expect($opf->getDcTitle())->toBeString();
    expect($opf->getDcCreators())->toBeArray();
    expect($opf->getDcDescription())->toBeString();
    expect($opf->getDcContributors())->toBeArray();
    expect($opf->getDcRights())->toBeArray();
    expect($opf->getDcPublisher())->toBeString();
    expect($opf->getDcIdentifiers())->toBeArray();
    expect($opf->getDcSubject())->toBeArray();
    expect($opf->getDcLanguage())->toBeString();
    expect($opf->getMetaItems())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
    expect($opf->getEpubVersionString())->toBeString();
    expect($opf->getEpubVersionString())->toMatch('/^[2-3]\.\d*/');
})->with([EPUB_OPF_EPUB2, EPUB_OPF_EPUB3, EPUB_OPF_INSURGENT, EPUB_OPF_LAGUERREETERNELLE, EPUB_OPF_EPEEETMORT, EPUB_OPF_NOT_FORMATTED]);

it('can parse epub opf meta items', function () {
    $opf = OpfItem::make(file_get_contents(EPUB_OPF_EPUB2), EPUB_OPF_EPUB2);

    $meta = $opf->getMetaItems();

    $title_sort = $opf->getMetaItem('calibre:title_sort');
    $series = $opf->getMetaItem('calibre:series');
    $series_index = $opf->getMetaItem('calibre:series_index');
    $timestamp = $opf->getMetaItem('calibre:timestamp');
    $rating = $opf->getMetaItem('calibre:rating');
    $cover = $opf->getMetaItem('cover');
    $author_link_map = $opf->getMetaItem('calibre:author_link_map');
    $not_exist = $opf->getMetaItem('not_exist');

    expect($meta)->toBeArray();
    expect(get_class($title_sort))->toBe(BookMeta::class);
    expect(get_class($series))->toBe(BookMeta::class);
    expect(get_class($series_index))->toBe(BookMeta::class);
    expect(get_class($timestamp))->toBe(BookMeta::class);
    expect(get_class($rating))->toBe(BookMeta::class);
    expect(get_class($cover))->toBe(BookMeta::class);
    expect(get_class($author_link_map))->toBe(BookMeta::class);
    expect($not_exist)->toBeNull();

    expect($title_sort->getContents())->toBeString();
    expect($series->getContents())->toBeString();
    expect($series_index->getContents())->toBeString();
    expect($timestamp->getContents())->toBeString();
    expect($rating->getContents())->toBeString();
    expect($cover->getContents())->toBeString();
    expect($author_link_map->getContents())->toBeString();

    expect($series->getContents())->toBe('Les Enfants de la Terre');
    expect($series_index->getContents())->toBe('1.0');
    expect($timestamp->getContents())->toBe('2023-03-25T10:32:21+00:00');
});

it('can parse epub opf alt', function () {
    $opf = OpfItem::make(file_get_contents(EPUB_OPF_EPUB3_ALT), EPUB_OPF_EPUB3_ALT);

    expect($opf->getMetadata())->toBeArray();
    expect($opf->getManifest())->toBeArray();
    expect($opf->getSpine())->toBeArray();
    expect($opf->getGuide())->toBeArray();
    expect($opf->getDcTitle())->toBeString();
    expect($opf->getDcCreators())->toBeArray();
    expect($opf->getDcDescription())->toBeString();
    expect($opf->getDcContributors())->toBeArray();
    expect($opf->getDcRights())->toBeArray();
    expect($opf->getDcPublisher())->toBeString();
    expect($opf->getDcIdentifiers())->toBeArray();
    expect($opf->getDcDate())->toBeNull();
    expect($opf->getDcSubject())->toBeArray();
    expect($opf->getDcLanguage())->toBeString();
    expect($opf->getMetaItems())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
});

it('can parse epub opf without tags', function () {
    $opf = OpfItem::make(file_get_contents(EPUB_OPF_EPUB2_NO_TAGS), EPUB_OPF_EPUB2_NO_TAGS);

    expect($opf)->tobeInstanceOf(OpfItem::class);
    expect(EPUB_OPF_EPUB2_NO_TAGS)->toBeReadableFile();
    expect($opf->getDcTitle())->toBeString();
    expect($opf->getDcCreators())->toBeArray();
    expect($opf->getDcDescription())->toBeString();
    expect($opf->getDcContributors())->toBeArray();
    expect($opf->getDcRights())->toBeArray();
    expect($opf->getDcPublisher())->toBeString();
    expect($opf->getDcIdentifiers())->toBeArray();
    expect($opf->getDcSubject())->toBeArray();
    expect($opf->getDcLanguage())->toBeString();
    expect($opf->getMetaItems())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
});

it('can parse epub opf with empty dc:creator', function (string $path) {
    $opf = OpfItem::make(file_get_contents($path), $path);

    expect($opf->getDcCreators())->toBeEmpty();
})->with([EPUB_OPF_EMPTY_CREATOR]);

it('can use float volume', function () {
    $opf = OpfItem::make(file_get_contents(EPUB_OPF_EPUB2_VOLUME_FLOAT));
    expect($opf->getMetaItem('calibre:series_index')->getContents())->toBe('1.5');
});

it('can use multiple authors', function (string $path) {
    $opf = OpfItem::make(file_get_contents($path));
    expect($opf->getDcCreators())->toHaveCount(2);
})->with([EPUB_OPF_MULTIPLE_AUTHORS, EPUB_OPF_MULTIPLE_AUTHORS_MERGE]);
