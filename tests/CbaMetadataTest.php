<?php

// CBAM comic-book-archive-metadata with ComicInfo.xml
// CBML comic-book-markup language with ComicBook.xml

it('can parse metadata', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);
    // expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    // expect($ebook->format())->toBe('cba');
    // expect($ebook->path())->toBe($path);
})->with([CBZ_CBAM]);
