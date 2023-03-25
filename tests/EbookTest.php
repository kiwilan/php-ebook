<?php

it('can create an instance of Ebook', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::make($path);

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->path())->toBe($path);
})->with(BOOKS_ITEMS);
