<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Mobi\Parser\ExthHeader;
use Kiwilan\Ebook\Formats\Mobi\Parser\ExthRecord;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiHeader;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;
use Kiwilan\Ebook\Formats\Mobi\Parser\PalmDOCHeader;
use Kiwilan\Ebook\Formats\Mobi\Parser\PalmRecord;

it('can parse mobi', function () {
    $ebook = Ebook::read(FORMAT_MOBI);

    expect($ebook->getTitle())->toBe("Alice's Adventures in Wonderland");
    expect($ebook->getAuthors()[0]->getName())->toBe('Tim Burton');
    expect($ebook->getAuthors()[1]->getName())->toBe('Lewis Carroll');
    expect($ebook->getDescription())->toBeString();
    expect($ebook->getPublisher())->toBeString();
    $identifiers = $ebook->getIdentifiers();
    expect($identifiers['isbn13']->getValue())->toBe('9780194229647');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getLanguage())->toBe('en');

    $tags = $ebook->getTags();
    expect($tags[0])->toBe('Classic');
});

it('can use mobi parser', function () {
    $parser = MobiParser::make(FORMAT_MOBI);

    expect($parser->getPalmDOCHeader())->toBeInstanceOf(PalmDOCHeader::class);
    expect($parser->getMobiHeader())->toBeInstanceOf(MobiHeader::class);
    expect($parser->getExthHeader())->toBeInstanceOf(ExthHeader::class);
    expect($parser->getExthRecords())->toBeArray();
    expect($parser->getPalmRecords())->toBeArray();
    expect($parser->getErrors())->toBeNull();

    expect($parser->getPalmDOCHeader()->compression)->toBe(2);
    expect($parser->getPalmDOCHeader()->textLength)->toBe(231532);
    expect($parser->getPalmDOCHeader()->recordSize)->toBe(4096);
    expect($parser->getPalmDOCHeader()->records)->toBe(57);

    expect($parser->getMobiHeader()->length)->toBe(232);
    expect($parser->getMobiHeader()->type)->toBe(2);
    expect($parser->getMobiHeader()->encoding)->toBe(65001);
    expect($parser->getMobiHeader()->id)->toBe(3127565514);
    expect($parser->getMobiHeader()->fileVersion)->toBe(6);

    expect($parser->getExthHeader()->length)->toBe(915);
    expect($parser->getExthHeader()->records)->toBeArray();

    expect($parser->getExthRecords()[0])->toBeInstanceOf(ExthRecord::class);
    expect($parser->getPalmRecords()[0])->toBeInstanceOf(PalmRecord::class);
});
