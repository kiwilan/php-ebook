<?php

use Kiwilan\Ebook\Ebook;

it('can parse pdf', function () {
    $ebook = Ebook::read(PDF);
    $firstAuthor = $ebook->authors()[0];

    expect($ebook->path())->toBe(PDF);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->title())->toBe('Example PDF');
    expect($ebook->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Ewilan Rivière');
    expect($ebook->description())->toBeString();
    expect($ebook->publisher())->toBe('Kiwilan');
    expect($ebook->publishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->publishDate()->format('Y-m-d H:i:s'))->toBe('2023-03-21 07:44:27');
    expect($ebook->tags())->toBeArray();
    expect($ebook->pagesCount())->toBe(4);
});

// it('can extract pdf cover', function () {
//     $ebook = Kiwilan\Ebook\Ebook::read(PDF);

//     $path = 'tests/output/cover-PDF.jpg';
//     file_put_contents($path, $ebook->cover());

//     expect($ebook->cover())->toBeString();
//     expect(file_exists($path))->toBeTrue();
//     expect($path)->toBeReadableFile();
// })->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');
