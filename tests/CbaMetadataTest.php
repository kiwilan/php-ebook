<?php

// CBAM comic-book-archive-metadata with ComicInfo.xml
// CBML comic-book-markup language with ComicBook.xml

use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;

it('can parse metadata', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);
    $book = $ebook->book();

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->format())->toBe('cba');
    expect($ebook->path())->toBe($path);
    expect($ebook->hasMetadata())->toBeTrue();

    expect($book->title())->toBe('You Had One Job');
    expect($book->series())->toBe('Fantastic Four');
    expect($book->volume())->toBe(22);
    expect($book->authors())->toBeArray();
    expect($book->authors())->toHaveCount(12);
    expect($book->authors()[0]->name())->toBe('Dan Slott');
    expect($book->publisher())->toBe('Marvel');
    expect($book->language())->toBe('en');
    expect($book->description())->toBeString();

    $date = new DateTime('2020-10-01');
    $bookDate = $book->date();
    expect($bookDate->format('Y-m-d'))->toBe($date->format('Y-m-d'));
    expect($book->pageCount())->toBe(24);
    expect($book->manga())->toBe(MangaEnum::NO);
    expect($book->ageRating())->toBe(AgeRatingEnum::UNKNOWN);

    expect($book->comicMeta()->imprint())->toBe('Vertigo');
    expect($book->comicMeta()->characters())->toBeArray();
    expect($book->comicMeta()->teams())->toBeArray();
    expect($book->comicMeta()->locations())->toBeArray();
    expect($book->comicMeta()->alternateSeries())->toBe('Empyre');
    expect($book->comicMeta()->seriesGroup())->toBe('Fantastic Four');
})->with([CBZ_CBAM]);
