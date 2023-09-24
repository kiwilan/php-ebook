<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Audio\AudiobookModule;
use Kiwilan\Ebook\Formats\Cba\CbaModule;
use Kiwilan\Ebook\Formats\EbookMetadata;
use Kiwilan\Ebook\Formats\Epub\EpubModule;
use Kiwilan\Ebook\Formats\Pdf\PdfModule;

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
        expect($metadata->getEpub())->toBeInstanceOf(EpubModule::class);
    }
    if ($metadata->isCba()) {
        expect($metadata->getCba())->toBeInstanceOf(CbaModule::class);
    }
    if ($metadata->isPdf()) {
        expect($metadata->getPdf())->toBeInstanceOf(PdfModule::class);
    }
    if ($metadata->isAudiobook()) {
        expect($metadata->getAudiobook())->toBeInstanceOf(AudiobookModule::class);
    }
})->with([EPUB, CBZ, PDF, AUDIOBOOK]);

it('can have description with HTML', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getDescription())->toBe('A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous land until she is found by a woman of the Clan, people very different from her own kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is destined to become their next leader sees her differences as a threat to his authority. He develops a deep and abiding hatred for the strange girl of the Others who lives in their midst, and is determined to get his revenge.');
    expect($ebook->getDescriptionHtml())->toBe('<div><p>A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous land until she is found by a woman of the Clan, people very different from her own kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is destined to become their next leader sees her differences as a threat to his authority. He develops a deep and abiding hatred for the strange girl of the Others who lives in their midst, and is determined to get his revenge.</p></div>');
})->with([EPUB]);
