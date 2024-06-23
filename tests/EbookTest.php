<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Audio\AudiobookModule;
use Kiwilan\Ebook\Formats\Cba\CbaModule;
use Kiwilan\Ebook\Formats\EbookParser;
use Kiwilan\Ebook\Formats\Epub\EpubModule;
use Kiwilan\Ebook\Formats\Pdf\PdfModule;

it('can create an instance of Ebook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getCreatedAt() instanceof DateTime)->toBeTrue();
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

    $parser = $ebook->getParser();
    expect($parser)->toBeInstanceOf(EbookParser::class);
    expect($ebook->getParser())->toBeInstanceOf(EbookParser::class);

    expect($ebook->getMetaTitle()->getSlug())->toBeString();

    if ($parser->isEpub()) {
        expect($parser->getEpub())->toBeInstanceOf(EpubModule::class);
    }
    if ($parser->isCba()) {
        expect($parser->getCba())->toBeInstanceOf(CbaModule::class);
    }
    if ($parser->isPdf()) {
        expect($parser->getPdf())->toBeInstanceOf(PdfModule::class);
    }
    if ($parser->isAudiobook()) {
        expect($parser->getAudiobook())->toBeInstanceOf(AudiobookModule::class);
    }
})->with([EPUB, CBZ, PDF, AUDIOBOOK]);

it('can have description with HTML', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getDescription())->toBeString();
    expect($ebook->getDescription())->toBe('<div>
      <p>A natural disaster leaves the young girl wandering alone in an unfamiliar and
      dangerous land until she is found by a woman of the Clan, people very different from her own
      kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those
      who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her
      with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the
      Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is
      destined to become their next leader sees her differences as a threat to his authority. He
      develops a deep and abiding hatred for the strange girl of the Others who lives in their
      midst, and is determined to get his revenge.</p></div>');

    $advancedDescription = $ebook->getDescriptionAdvanced();

    expect($advancedDescription->getDescription())->toBeString();
    expect($advancedDescription->getDescription())->toBe('<div>
      <p>A natural disaster leaves the young girl wandering alone in an unfamiliar and
      dangerous land until she is found by a woman of the Clan, people very different from her own
      kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those
      who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her
      with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the
      Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is
      destined to become their next leader sees her differences as a threat to his authority. He
      develops a deep and abiding hatred for the strange girl of the Others who lives in their
      midst, and is determined to get his revenge.</p></div>');

    $limited = $advancedDescription->getDescription(100);
    expect(strlen($limited))->toBe(100);
    expect($limited)->toBe('<div>
      <p>A natural disaster leaves the young girl wandering alone in an unfamiliar and
    …');

    expect($advancedDescription->toHtml())->toBeString();
    expect($advancedDescription->toHtml())->toBe('<div><p>A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous land until she is found by a woman of the Clan, people very different from her own kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is destined to become their next leader sees her differences as a threat to his authority. He develops a deep and abiding hatred for the strange girl of the Others who lives in their midst, and is determined to get his revenge.</p></div>');

    $limited = $advancedDescription->toHtml(100);
    expect(strlen($limited))->toBe(100);
    expect($limited)->toBe('<div><p>A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous l…');

    expect($advancedDescription->toString())->toBeString();
    expect($advancedDescription->toString())->toBe('A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous land until she is found by a woman of the Clan, people very different from her own kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is destined to become their next leader sees her differences as a threat to his authority. He develops a deep and abiding hatred for the strange girl of the Others who lives in their midst, and is determined to get his revenge.');

    $limited = $advancedDescription->toString(100);
    expect(strlen($limited))->toBe(100);
    expect($limited)->toBe('A natural disaster leaves the young girl wandering alone in an unfamiliar and dangerous land unti…');

    expect($advancedDescription->toStringMultiline())->toBeString();
    expect($advancedDescription->toStringMultiline())->toBe('A natural disaster leaves the young girl wandering alone in an unfamiliar and
      dangerous land until she is found by a woman of the Clan, people very different from her own
      kind. To them, blond, blue-eyed Ayla looks peculiar and ugly—she is one of the Others, those
      who have moved into their ancient homeland; but Iza cannot leave the girl to die and takes her
      with them. Iza and Creb, the old Mog-ur, grow to love her, and as Ayla learns the ways of the
      Clan and Iza’s way of healing, most come to accept her. But the brutal and proud youth who is
      destined to become their next leader sees her differences as a threat to his authority. He
      develops a deep and abiding hatred for the strange girl of the Others who lives in their
      midst, and is determined to get his revenge.');

    $limited = $advancedDescription->toStringMultiline(100);
    expect(strlen($limited))->toBe(100);
    expect($limited)->toBe('A natural disaster leaves the young girl wandering alone in an unfamiliar and
      dangerous lan…');
})->with([EPUB]);
