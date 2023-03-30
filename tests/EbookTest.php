<?php

it('can create an instance of Ebook', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->path())->toBe($path);
})->with(BOOKS_ITEMS);

it('can create PDF an instance of Ebook', function () {
    $ebook = Kiwilan\Ebook\Ebook::read(PDF);

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->path())->toBe(PDF);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');
