<?php

// CBAM comic-book-archive-metadata with ComicInfo.xml
// CBML comic-book-markup language with ComicBook.xml

use Kiwilan\Ebook\Cba\CbaCbam;
use Kiwilan\Ebook\Cba\CbaFormat;
use Kiwilan\Ebook\Entity\ComicMeta;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\Ebook\XmlReader;

it('can parse cba', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Kiwilan\Ebook\Ebook::class);
    expect($ebook->format())->toBe('cba');
    expect($ebook->path())->toBe($path);
})->with([...CBA_ITEMS, CBZ_CBAM]);

it('can parse no metadata', function () {
    $ebook = Kiwilan\Ebook\Ebook::read(CBZ_NO_METADATA);

    expect($ebook->hasMetadata())->toBeFalse();
});

it('can parse ComicInfo basic', function () {
    $metadata = XmlReader::toArray(file_get_contents(COMIC_INFO_BASIC));
    $cba = CbaCbam::create($metadata);

    expect($cba->title())->toBe('Grise Bouille, Tome I');
    expect($cba->series())->toBe('Grise Bouille');
    expect($cba->number())->toBe(1);
    expect($cba->writers())->toBeArray()
        ->toHaveCount(1)
        ->toContain('Simon « Gee » Giraudot');
    expect($cba->publisher())->toBe('Des livres en Communs (Framasoft)');
    expect($cba->language())->toBe('fr');
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
    $ebook = Kiwilan\Ebook\Ebook::read($path);

    $path = 'tests/output/cover-cba.jpg';
    file_put_contents($path, $ebook->cover());

    expect($ebook->cover())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
})->with([...CBA_ITEMS, CBZ_CBAM]);

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
    expect($book->authorFirst()->name())->toBe('Dan Slott');
    expect($book->authors())->toHaveCount(12);
    expect($book->authors()[0]->name())->toBe('Dan Slott');
    expect($book->publisher())->toBe('Marvel');
    expect($book->language())->toBe('en');
    expect($book->description())->toBeString();

    expect($book->editors())->toBeArray();
    expect($book->editors())->toHaveCount(3);
    expect($book->editors()[0])->toBe('Alanna Smith');

    expect($book->review())->toBeNull();
    expect($book->web())->toBeString();
    expect($book->isBlackAndWhite())->toBeFalse();
    expect($book->comicMeta())->toBeInstanceOf(ComicMeta::class);
    expect($book->extras())->toBeArray();

    $date = new DateTime('2020-10-01');
    expect($book->date()->format('Y-m-d'))->toBe($date->format('Y-m-d'));
    expect($book->pageCount())->toBe(24);
    expect($book->manga())->toBe(MangaEnum::NO);
    expect($book->ageRating())->toBe(AgeRatingEnum::UNKNOWN);

    expect($book->comicMeta()->imprint())->toBe('Vertigo');
    expect($book->comicMeta()->characters())->toBeArray();
    expect($book->comicMeta()->teams())->toBeArray();
    expect($book->comicMeta()->locations())->toBeArray();
    expect($book->comicMeta()->alternateSeries())->toBe('Empyre');
    expect($book->comicMeta()->alternateCount())->toBeNull();
    expect($book->comicMeta()->alternateNumber())->toBeNull();
    expect($book->comicMeta()->seriesGroup())->toBe('Fantastic Four');
    expect($book->comicMeta()->count())->toBeNull();
    expect($book->comicMeta()->volume())->toBeNull();
    expect($book->comicMeta()->storyArc())->toBeNull();
    expect($book->comicMeta()->storyArcNumber())->toBeNull();
})->with([CBZ_CBAM]);

it('can parse CbaFormat', function (string $path) {
    $ebook = Kiwilan\Ebook\Ebook::read($path);
    $metadata = $ebook->metadata();

    expect($metadata)->toBeInstanceOf(CbaFormat::class);
    expect($metadata->title())->toBe('You Had One Job');
    expect($metadata->series())->toBe('Fantastic Four');
    expect($metadata->number())->toBe(22);
    expect($metadata->writers())->toBeArray();
    expect($metadata->writers())->toHaveCount(1);
    expect($metadata->writers()[0])->toBe('Dan Slott');
    expect($metadata->publisher())->toBe('Marvel');
    expect($metadata->language())->toBe('en');
    expect($metadata->summary())->toBeString();

    expect($metadata->pencillers())->toBeArray();
    expect($metadata->inkers())->toBeArray();
    expect($metadata->colorists())->toBeArray();
    expect($metadata->letterers())->toBeArray();
    expect($metadata->coverArtists())->toBeArray();
    expect($metadata->translators())->toBeArray();

    expect($metadata->genres())->toBeArray();
    expect($metadata->genres())->toHaveCount(1);

    expect($metadata->characters())->toBeArray();
    expect($metadata->teams())->toBeArray();
    expect($metadata->locations())->toBeArray();

    expect($metadata->alternateSeries())->toBe('Empyre');
    expect($metadata->seriesGroup())->toBe('Fantastic Four');
    expect($metadata->ageRating())->toBeNull();
    expect($metadata->manga())->toBe(MangaEnum::NO);
    expect($metadata->pageCount())->toBe(24);
    expect($metadata->imprint())->toBe('Vertigo');
    expect($metadata->web())->toBe('https://comicvine.gamespot.com/fantastic-four-22-you-had-one-job/4000-787351/');
    expect($metadata->notes())->toBeString();
    expect($metadata->gtin())->toBeArray();
    expect($metadata->extras())->toBeArray();
    expect($metadata->editors())->toBeArray();
    expect($metadata->scanInformation())->toBeString();
    expect($metadata->communityRating())->toBeNull();
    expect($metadata->isBlackAndWhite())->toBeFalse();

    expect($metadata->review())->toBeNull();
    expect($metadata->alternateSeries())->toBeString();
    expect($metadata->alternateNumber())->toBeNull();
    expect($metadata->alternateCount())->toBeNull();
    expect($metadata->count())->toBeNull();
    expect($metadata->volume())->toBeNull();
    expect($metadata->storyArc())->toBeNull();
    expect($metadata->storyArcNumber())->toBeNull();
    expect($metadata->seriesGroup())->toBeString();

    expect($metadata->mainCharacterOrTeam())->toBeNull();
    expect($metadata->format())->toBe('CBZ');

    $date = new DateTime('2020-10-01');
    expect($metadata->date()->format('Y-m-d'))->toBe($date->format('Y-m-d'));

    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
    expect($metadata->__toString())->toBeString();
})->with([CBZ_CBAM]);

it('can use ComicMeta', function () {
    $meta = new ComicMeta();

    $meta->setCharacters(['character 1', 'character 2']);
    $meta->setTeams(['team 1', 'team 2']);
    $meta->setLocations(['location 1', 'location 2']);
    $meta->setAlternateSeries('alternate series');
    $meta->setAlternateNumber(1);
    $meta->setAlternateCount('alternate count');
    $meta->setCount(1);
    $meta->setVolume(1);
    $meta->setStoryArc('story arc');
    $meta->setStoryArcNumber(1);
    $meta->setSeriesGroup('series group');
    $meta->setImprint('imprint');

    expect($meta->characters())->toBeArray();
    expect($meta->characters())->toHaveCount(2);
    expect($meta->characters()[0])->toBe('character 1');
    expect($meta->teams())->toBeArray();
    expect($meta->teams())->toHaveCount(2);
    expect($meta->teams()[0])->toBe('team 1');
    expect($meta->locations())->toBeArray();
    expect($meta->locations())->toHaveCount(2);
    expect($meta->locations()[0])->toBe('location 1');
    expect($meta->alternateSeries())->toBe('alternate series');
    expect($meta->alternateNumber())->toBe(1);
    expect($meta->alternateCount())->toBe('alternate count');
    expect($meta->count())->toBe(1);
    expect($meta->volume())->toBe(1);
    expect($meta->storyArc())->toBe('story arc');
    expect($meta->storyArcNumber())->toBe(1);
    expect($meta->seriesGroup())->toBe('series group');
    expect($meta->imprint())->toBe('imprint');
});
