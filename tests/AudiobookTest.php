<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Models\BookAuthor;
use Pest\Expectation;

it('can parse audiobook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getpath())->toBe($path);

    $metadata = $ebook->getParser();
    expect($metadata->getAudiobook()->getAudio())->toBeArray();
    expect($ebook->getExtras())->toBeArray();
    expect($metadata->getAudiobook()->toArray())->toBeArray();
    expect($metadata->getAudiobook()->toJson())->toBeString();
    expect($metadata->getAudiobook()->__toString())->toBeString();
})->with(AUDIOBOOK_ITEMS);

it('can parse audiobook (basic)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getTitle())->toBe('Introduction');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    // expect($ebook->getLanguage())->toBe('en');
    expect($ebook->getPublisher())->toBe('P1PDD & Mr Piouf');
    expect($ebook->getExtra('comment'))->toBe('http://www.p1pdd.com');
    expect($ebook->getSeries())->toBe('P1PDD Le conclave de Troie');
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getPagesCount())->toBe(11);
})->with([AUDIOBOOK, AUDIOBOOK_M4B, AUDIOBOOK_PART_1, AUDIOBOOK_PART_2]);

it('can parse audiobook (advanced)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getTitle())->toBe('Audiobook Test');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    expect($ebook->getPublisher())->toBe('Ewilan');
    expect($ebook->getDescription())->toBe('Epic story about audiobooks.');
    expect($ebook->getExtra('comment'))->toBe('Do you want to extract an audiobook?');
    expect($ebook->getSeries())->toBe('Audiobook Test');
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getCopyright())->toBe('Ewilan Rivière');
    expect($ebook->getPagesCount())->toBe(22);
})->with([AUDIOBOOK_CHAPTERS]);
