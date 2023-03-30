<?php

use Kiwilan\Ebook\Cba\CbaXml;

it('can parse cba', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->format())->toBe('cba');
    expect($ebook->path())->toBe($path);
})->with(CBA_ITEMS);

it('can parse no metadata', function () {
    $ebook = Kiwilan\Ebook\Ebook::read(CBZ_NO_METADATA);

    expect($ebook->hasMetadata())->toBeFalse();
});

it('can parse ComicInfo basic', function () {
    $cba = CbaXml::read(file_get_contents(COMIC_INFO_BASIC));

    expect($cba->title())->toBe('Grise Bouille, Tome I');
    expect($cba->series())->toBe('Grise Bouille');
    expect($cba->number())->toBe(1);
    expect($cba->writer())->toBe('Simon « Gee » Giraudot');
    expect($cba->publisher())->toBe('Des livres en Communs (Framasoft)');
    expect($cba->languageIso())->toBe('fr');
});

it('can parse ComicInfo basic cba book', function (string $path) {
    $book = Kiwilan\Ebook\Ebook::read($path)->book();

    expect($book->title())->toBe('Grise Bouille, Tome I');
    expect($book->series())->toBe('Transmetropolitain');
    expect($book->volume())->toBe(1);
    expect($book->authors())->toBeArray();
    expect($book->authors())->toHaveCount(1);
    expect($book->authors()[0]->name())->toBe('Simon « Gee » Giraudot');
    expect($book->publisher())->toBe('Des livres en Communs (Framasoft)');
    expect($book->language())->toBe('fr');
})->with(CBA_ITEMS);

it('can extract cba cover', function (string $path) {
    $book = Kiwilan\Ebook\Ebook::read($path)->book();

    $path = 'tests/output/cover-cba.jpg';
    file_put_contents($path, $book->cover());

    expect($book->cover())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
})->with(CBA_ITEMS);
