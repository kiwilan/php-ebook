<?php

use Kiwilan\Ebook\Epub\EpubContainer;
use Kiwilan\Ebook\Epub\EpubOpf;

it('can parse epub container', function (string $path) {
    $container = EpubContainer::make(file_get_contents($path));

    expect($path)->toBeReadableFile();
    expect($container->opfPath())->toBeString();
})->with([EPUB_CONTAINER_EPUB2, EPUB_CONTAINER_EPUB3]);

it('can parse epub opf', function (string $path) {
    $opf = EpubOpf::make(file_get_contents($path));

    expect($path)->toBeReadableFile();
    expect($opf->dcTitle())->toBeString();
    expect($opf->dcCreators())->toBeArray();
    expect($opf->dcDescription())->toBeString();
    expect($opf->dcContributors())->toBeArray();
    expect($opf->dcRights())->toBeArray();
    expect($opf->dcPublisher())->toBeString();
    expect($opf->dcIdentifiers())->toBeArray();
    expect($opf->dcDate())->toBeInstanceOf(DateTime::class);
    expect($opf->dcSubject())->toBeArray();
    expect($opf->dcLanguage())->toBeString();
    expect($opf->meta())->toBeArray();
    expect($opf->coverPath())->toBeString();
})->with([EPUB_OPF_EPUB2, EPUB_OPF_EPUB3]);
