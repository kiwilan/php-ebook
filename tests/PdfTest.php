<?php

use Kiwilan\Archive\Readers\ArchivePdf;
use Kiwilan\Ebook\Ebook;

it('can parse pdf', function () {
    $ebook = Ebook::read(PDF);
    $firstAuthor = $ebook->getAuthors()[0];

    expect($ebook->getpath())->toBe(PDF);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBe('Vue3 Composition API');
    expect($ebook->getAuthors())->toBeArray();
    expect($firstAuthor->getName())->toBe('Vue Mastery');
    expect($ebook->getDescription())->toBeString();
    expect($ebook->getPublisher())->toBe('Vue Mastery PDF');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe('2023-03-21 07:44:27');
    expect($ebook->getTags())->toBeArray();
    expect($ebook->getPagesCount())->toBe(4);
});

it('can extract pdf cover', function () {
    $ebook = Ebook::read(PDF);

    $path = 'tests/output/cover-PDF.jpg';
    file_put_contents($path, $ebook->getCover()->getContents());

    expect($ebook->getCover()->getContents())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
    expect(fileIsValidImg($path))->toBeTrue();
})->skip(PHP_OS_FAMILY === 'Windows' || PHP_VERSION >= '8.3', 'Skip on Windows or PHP >= 8.3');

it('can parse empty pdf', function () {
    $ebook = Ebook::read(PDF_EMPTY);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBeNull();
    expect($ebook->getArchive())->toBeInstanceOf(ArchivePdf::class);
});

it('can parse simple pdf', function () {
    $ebook = Ebook::read(PDF_SIMPLE);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBeString();
    expect($ebook->getArchive())->toBeInstanceOf(ArchivePdf::class);
});
