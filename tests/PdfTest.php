<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCore;

// it('can parse pdf', function () {
//     $ebook = Ebook::read(PDF);
//     $core = $ebook->core();
//     $firstAuthor = $core->authors()[0];

//     expect($ebook->path())->toBe(PDF);

//     expect($core)->toBeInstanceOf(EbookCore::class);
//     expect($core->title())->toBe('Example PDF');
//     expect($core->authors())->toBeArray();
//     expect($firstAuthor->name())->toBe('Ewilan Rivière');
//     expect($core->description())->toBeString();
//     expect($core->publisher())->toBe('Kiwilan');
//     expect($core->publishDate())->toBeInstanceOf(DateTime::class);
//     expect($core->publishDate()->format('Y-m-d H:i:s'))->toBe('2023-03-21 07:44:27');
//     expect($core->tags())->toBeArray();
//     // expect($core->pageCount())->toBe(4);
// });

// it('can extract pdf cover', function () {
//     $ebook = Kiwilan\Ebook\Ebook::read(PDF);

//     $path = 'tests/output/cover-PDF.jpg';
//     file_put_contents($path, $ebook->cover());

//     expect($ebook->cover())->toBeString();
//     expect(file_exists($path))->toBeTrue();
//     expect($path)->toBeReadableFile();
// })->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');
