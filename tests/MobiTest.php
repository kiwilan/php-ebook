<?php

use Kiwilan\Ebook\Ebook;

it('can parse mobi', function () {
    $ebook = Ebook::read(STANDARD_MOBI);
    ray($ebook->metadata()->mobi());

    expect($ebook->title())->toBe("Alice's Adventures in Wonderland");
    expect($ebook->authors()[0]->name())->toBe('Lewis Carroll');
    expect($ebook->authors()[1]->name())->toBe('Tim Burton');
    expect($ebook->description())->toBeString();
    expect($ebook->publisher())->toBeString();
    expect($ebook->identifiers()[0]->content())->toBe('9780194229647');
    expect($ebook->publishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->language())->toBe('en');
    expect($ebook->tags()[0])->toBe('Fictions');
});
