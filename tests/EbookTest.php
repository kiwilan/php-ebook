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
    expect($ebook->path())->toBe($path);
    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();
})->with(BOOKS_ITEMS);

it('can create PDF an instance of Ebook', function () {
    $ebook = Ebook::read(PDF);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->path())->toBe(PDF);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse ebooks', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->path())->toBe($path);
    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();

    expect($ebook->title())->toBeString();
})->with(EBOOKS_ITEMS);

it('can parse metadata', function (string $path) {
    $ebook = Ebook::read($path);

    $metadata = $ebook->metadata();
    expect($metadata)->toBeInstanceOf(EbookMetadata::class);

    if ($metadata->isEpub()) {
        expect($metadata->epub())->toBeInstanceOf(EpubMetadata::class);
    }
    if ($metadata->isCba()) {
        expect($metadata->cba())->toBeInstanceOf(CbaMetadata::class);
    }
    if ($metadata->isPdf()) {
        expect($metadata->pdf())->toBeInstanceOf(PdfMetadata::class);
    }
    if ($metadata->isAudiobook()) {
        expect($metadata->audiobook())->toBeInstanceOf(AudiobookMetadata::class);
    }
})->with([EPUB, CBZ, PDF, AUDIOBOOK]);
