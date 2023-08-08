<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Audio\AudiobookMetadata;
use Kiwilan\Ebook\Formats\Cba\CbaMetadata;
use Kiwilan\Ebook\Formats\EbookMetadata;
use Kiwilan\Ebook\Formats\Epub\EpubMetadata;
use Kiwilan\Ebook\Formats\Pdf\PdfMetadata;

it('can create an instance of Ebook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getpath())->toBe($path);
    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();
})->with(BOOKS_ITEMS);

it('can create PDF an instance of Ebook', function () {
    $ebook = Ebook::read(PDF);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getpath())->toBe(PDF);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse ebooks', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getpath())->toBe($path);
    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();

    expect($ebook->getTitle())->toBeString();
})->with(EBOOKS_ITEMS);

it('can parse metadata', function (string $path) {
    $ebook = Ebook::read($path);

    $metadata = $ebook->getMetadata();
    expect($metadata)->toBeInstanceOf(EbookMetadata::class);
    expect($ebook->getMetaTitle()->getUniqueFilename())->toBeString();

    if ($metadata->isEpub()) {
        expect($metadata->getEpub())->toBeInstanceOf(EpubMetadata::class);
    }
    if ($metadata->isCba()) {
        expect($metadata->getCba())->toBeInstanceOf(CbaMetadata::class);
    }
    if ($metadata->isPdf()) {
        expect($metadata->getPdf())->toBeInstanceOf(PdfMetadata::class);
    }
    if ($metadata->isAudiobook()) {
        expect($metadata->getAudiobook())->toBeInstanceOf(AudiobookMetadata::class);
    }
})->with([EPUB, CBZ, PDF, AUDIOBOOK]);
