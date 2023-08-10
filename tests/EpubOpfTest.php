<?php

use Kiwilan\Ebook\Formats\Epub\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\OpfMetadata;
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
    $opf = OpfMetadata::make(file_get_contents($path), $path);

    expect($opf)->tobeInstanceOf(OpfMetadata::class);
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
    expect($opf->getMeta())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
})->with([EPUB_OPF_EPUB2, EPUB_OPF_EPUB3, EPUB_OPF_INSURGENT, EPUB_OPF_LAGUERREETERNELLE, EPUB_OPF_EPEEETMORT, EPUB_OPF_NOT_FORMATTED]);

it('can parse epub opf alt', function () {
    $opf = OpfMetadata::make(file_get_contents(EPUB_OPF_EPUB3_ALT), EPUB_OPF_EPUB3_ALT);

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
    expect($opf->getMeta())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
});

it('can parse epub opf without tags', function () {
    $opf = OpfMetadata::make(file_get_contents(EPUB_OPF_EPUB2_NO_TAGS), EPUB_OPF_EPUB2_NO_TAGS);

    expect($opf)->tobeInstanceOf(OpfMetadata::class);
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
    expect($opf->getMeta())->toBeArray();
    expect($opf->getCoverPath())->toBeString();
    expect($opf->getEpubVersion())->toBeGreaterThanOrEqual(2);
});
