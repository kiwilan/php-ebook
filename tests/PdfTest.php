<?php

it('can parse pdf', function () {
    $book = Kiwilan\Ebook\Ebook::make(PDF)->book();
    $firstAuthor = $book->authors()[0];

    expect($book)->toBeInstanceOf(Kiwilan\Ebook\EbookEntity::class);
    expect($book->path())->toBe(PDF);
    expect($book->title())->toBe('Example PDF');
    expect($book->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Ewilan Rivière');
    expect($book->description())->toBeString();
    expect($book->publisher())->toBe('Kiwilan');
    expect($book->date())->toBeInstanceOf(DateTime::class);
    expect($book->date()->format('Y-m-d H:i:s'))->toBe('2023-03-21 07:44:27');
    expect($book->tags())->toBeArray();
    expect($book->pageCount())->toBe(4);
});
