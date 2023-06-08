<?php

use Kiwilan\Ebook\Epub\EpubContainer;
use Kiwilan\Ebook\Epub\OpfMetadata;
use Kiwilan\Ebook\XmlReader;

// it('can parse epub container', function (string $path) {
//     $container = EpubContainer::make(file_get_contents($path));

//     expect($path)->toBeReadableFile();
//     expect($container->opfPath())->toBeString();
//     expect($container->version())->toBeString();
// })->with([EPUB_CONTAINER_EPUB2, EPUB_CONTAINER_EPUB3]);

// it('can failed if bad file', function () {
//     expect(fn () => EpubContainer::make(file_get_contents(EPUB_CONTAINER_EPUB2_BAD)))->toThrow(Exception::class);
// });

// it('can failed if empty file', function () {
//     expect(fn () => EpubContainer::make(file_get_contents(EPUB_CONTAINER_EPUB2_EMPTY)))->toThrow(Exception::class);
// });

// it('can failed with wrong XML', function () {
//     expect(fn () => XmlReader::toArray('<html><body><body></html>'))->toThrow(Exception::class);
// });

// it('can parse epub opf', function (string $path) {
//     $opf = OpfMetadata::make(file_get_contents($path), $path);

//     expect($opf)->tobeInstanceOf(OpfMetadata::class);
//     expect($path)->toBeReadableFile();
//     expect($opf->dcTitle())->toBeString();
//     expect($opf->dcCreators())->toBeArray();
//     expect($opf->dcDescription())->toBeString();
//     expect($opf->dcContributors())->toBeArray();
//     expect($opf->dcRights())->toBeArray();
//     expect($opf->dcPublisher())->toBeString();
//     expect($opf->dcIdentifiers())->toBeArray();
//     expect($opf->dcSubject())->toBeArray();
//     expect($opf->dcLanguage())->toBeString();
//     expect($opf->meta())->toBeArray();
//     expect($opf->coverPath())->toBeString();
//     expect($opf->epubVersion())->toBeGreaterThanOrEqual(2);
// })->with([EPUB_OPF_EPUB2, EPUB_OPF_EPUB3]);

// it('can parse epub opf alt', function () {
//     $opf = OpfMetadata::make(file_get_contents(EPUB_OPF_EPUB3_ALT), EPUB_OPF_EPUB3_ALT);

//     expect($opf->metadata())->toBeArray();
//     expect($opf->manifest())->toBeArray();
//     expect($opf->spine())->toBeArray();
//     expect($opf->guide())->toBeArray();
//     expect($opf->dcTitle())->toBeString();
//     expect($opf->dcCreators())->toBeArray();
//     expect($opf->dcDescription())->toBeString();
//     expect($opf->dcContributors())->toBeArray();
//     expect($opf->dcRights())->toBeArray();
//     expect($opf->dcPublisher())->toBeString();
//     expect($opf->dcIdentifiers())->toBeArray();
//     expect($opf->dcDate())->toBeNull();
//     expect($opf->dcSubject())->toBeArray();
//     expect($opf->dcLanguage())->toBeString();
//     expect($opf->meta())->toBeArray();
//     expect($opf->coverPath())->toBeString();
//     expect($opf->epubVersion())->toBeGreaterThanOrEqual(2);
// });
