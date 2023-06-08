<?php

use Kiwilan\Ebook\Ebook;

it('can create an instance of Ebook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->path())->toBe($path);
    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();
})->with(BOOKS_ITEMS);

// it('can create PDF an instance of Ebook', function () {
//     $ebook = Ebook::read(PDF);

//     expect($ebook)->toBeInstanceOf(Ebook::class);
//     expect($ebook->path())->toBe(PDF);
// })->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

// it('can parse ebooks', function (string $path) {
//     $ebook = Ebook::read($path);
//     $core = $ebook->core();

//     expect($ebook)->toBeInstanceOf(Ebook::class);
//     expect($ebook->path())->toBe($path);
//     expect($ebook->toArray())->toBeArray();
//     expect($ebook->toJson())->toBeString();
//     expect($ebook->__toString())->toBeString();

//     expect($core->title())->toBeString();
// })->with(EBOOKS_ITEMS);
