<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Tools\BookAuthor;
use Pest\Expectation;

it('can parse audiobook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->path())->toBe($path);

    $metadata = $ebook->metadata();
    expect($metadata->audiobook()->audio())->toBeArray();
    expect($metadata->audiobook()->toArray())->toBeArray();
    expect($metadata->audiobook()->toJson())->toBeString();
    expect($metadata->audiobook()->__toString())->toBeString();
})->with(AUDIOBOOK_ITEMS);

it('can parse audiobook (basic)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->title())->toBe('Introduction');
    expect($ebook->authors())->toBeArray();
    expect($ebook->authors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    // expect($ebook->language())->toBe('en');
    expect($ebook->publisher())->toBe('P1PDD & Mr Piouf');
    expect($ebook->description())->toBe('http://www.p1pdd.com');
    expect($ebook->series())->toBe('P1PDD Le conclave de Troie');
    expect($ebook->volume())->toBe(1);
    expect($ebook->pagesCount())->toBe(11);
})->with([AUDIOBOOK, AUDIOBOOK_M4B, AUDIOBOOK_PART_1, AUDIOBOOK_PART_2]);

it('can parse audiobook (advanced)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->title())->toBe('Audiobook Test');
    expect($ebook->authors())->toBeArray();
    expect($ebook->authors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    expect($ebook->publisher())->toBe('Ewilan');
    expect($ebook->description())->toBe('Epic story about audiobooks. Do you want to extract an audiobook?');
    expect($ebook->series())->toBe('Audiobook Test');
    expect($ebook->volume())->toBe(1);
    expect($ebook->copyright())->toBe('Ewilan Rivière');
    expect($ebook->pagesCount())->toBe(22);
})->with([AUDIOBOOK_CHAPTERS]);
