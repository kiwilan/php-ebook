<?php

// CBAM comic-book-archive-metadata with ComicInfo.xml
// CBML comic-book-markup language with ComicBook.xml

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\EbookFormatEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\Ebook\Formats\Cba\CbamMetadata;
use Kiwilan\Ebook\Tools\ComicMeta;
use Kiwilan\XmlReader\XmlReader;

it('can parse cba', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->format())->toBe(EbookFormatEnum::CBA);
    expect($ebook->path())->toBe($path);
})->with([...CBA_ITEMS, CBZ_CBAM]);

it('can parse no metadata', function () {
    $ebook = Ebook::read(CBZ_NO_METADATA);

    expect($ebook->hasMetadata())->toBeFalse();
});

it('can parse ComicInfo basic', function () {
    $metadata = XmlReader::make(file_get_contents(COMIC_INFO_BASIC));
    $cba = CbamMetadata::make($metadata->content());

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
    $ebook = Ebook::read($path);

    expect($ebook->title())->toBe('Grise Bouille, Tome I');
    expect($ebook->series())->toBe('Transmetropolitain');
    expect($ebook->volume())->toBe(1);
    expect($ebook->authors())->toBeArray();
    expect($ebook->authors())->toHaveCount(1);
    expect($ebook->authors()[0]->name())->toBe('Simon « Gee » Giraudot');
    expect($ebook->publisher())->toBe('Des livres en Communs (Framasoft)');
    expect($ebook->language())->toBe('fr');
})->with(CBA_ITEMS);

it('can extract cba cover', function (string $path) {
    $ebook = Ebook::read($path);

    $path = 'tests/output/cover-cba.jpg';
    file_put_contents($path, $ebook->cover());

    expect($ebook->cover()->content())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
})->with([...CBA_ITEMS, CBZ_CBAM]);

it('can parse ComicMeta', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->format())->toBe(EbookFormatEnum::CBA);
    expect($ebook->path())->toBe($path);
    expect($ebook->hasMetadata())->toBeTrue();

    expect($ebook->title())->toBe('You Had One Job');
    expect($ebook->series())->toBe('Fantastic Four');
    expect($ebook->volume())->toBe(22);
    expect($ebook->authors())->toBeArray();
    expect($ebook->authorMain()->name())->toBe('Dan Slott');
    expect($ebook->authors())->toHaveCount(12);
    expect($ebook->authors()[0]->name())->toBe('Dan Slott');
    expect($ebook->publisher())->toBe('Marvel');
    expect($ebook->language())->toBe('en');
    expect($ebook->description())->toBeString();

    $date = new DateTime('2020-10-01');
    expect($ebook->publishDate()->format('Y-m-d'))->toBe($date->format('Y-m-d'));
    expect($ebook->pagesCount())->toBe(24);

    expect($ebook->extras())->toBeArray();
    /** @var ComicMeta $comicMeta */
    $comicMeta = $ebook->extras()['comicMeta'];

    expect($comicMeta)->toBeInstanceOf(ComicMeta::class);
    expect($comicMeta->editors())->toBeArray();
    expect($comicMeta->editors())->toHaveCount(3);
    expect($comicMeta->editors()[0])->toBe('Alanna Smith');
    expect($comicMeta->review())->toBeNull();
    expect($comicMeta->web())->toBeString();
    expect($comicMeta->isBlackAndWhite())->toBeFalse();
    expect($comicMeta->manga())->toBe(MangaEnum::NO);
    expect($comicMeta->ageRating())->toBe(AgeRatingEnum::TEEN);
    expect($comicMeta->imprint())->toBe('Vertigo');
    expect($comicMeta->characters())->toBeArray();
    expect($comicMeta->teams())->toBeArray();
    expect($comicMeta->locations())->toBeArray();
    expect($comicMeta->alternateSeries())->toBe('Empyre');
    expect($comicMeta->alternateCount())->toBeNull();
    expect($comicMeta->alternateNumber())->toBeNull();
    expect($comicMeta->seriesGroup())->toBe('Fantastic Four');
    expect($comicMeta->count())->toBeNull();
    expect($comicMeta->volume())->toBeNull();
    expect($comicMeta->storyArc())->toBeNull();
    expect($comicMeta->storyArcNumber())->toBeNull();
})->with([CBZ_CBAM]);

it('can parse CbamMetadata', function (string $path) {
    $ebook = Ebook::read($path);
    $cbam = $ebook->metadata()->cba()?->cbam();

    if (! $cbam) {
        throw new Exception('CBAM is null');
    }

    expect($cbam)->toBeInstanceOf(CbamMetadata::class);
    expect($cbam->title())->toBe('You Had One Job');
    expect($cbam->series())->toBe('Fantastic Four');
    expect($cbam->number())->toBe(22);
    expect($cbam->writers())->toBeArray();
    expect($cbam->writers())->toHaveCount(1);
    expect($cbam->writers()[0])->toBe('Dan Slott');
    expect($cbam->publisher())->toBe('Marvel');
    expect($cbam->language())->toBe('en');
    expect($cbam->summary())->toBeString();

    expect($cbam->pencillers())->toBeArray();
    expect($cbam->inkers())->toBeArray();
    expect($cbam->colorists())->toBeArray();
    expect($cbam->letterers())->toBeArray();
    expect($cbam->coverArtists())->toBeArray();
    expect($cbam->translators())->toBeArray();

    expect($cbam->genres())->toBeArray();
    expect($cbam->genres())->toHaveCount(1);

    expect($cbam->characters())->toBeArray();
    expect($cbam->teams())->toBeArray();
    expect($cbam->locations())->toBeArray();

    expect($cbam->alternateSeries())->toBe('Empyre');
    expect($cbam->seriesGroup())->toBe('Fantastic Four');
    expect($cbam->ageRating())->toBe(AgeRatingEnum::TEEN);
    expect($cbam->manga())->toBe(MangaEnum::NO);
    expect($cbam->pageCount())->toBe(24);
    expect($cbam->imprint())->toBe('Vertigo');
    expect($cbam->web())->toBe('https://comicvine.gamespot.com/fantastic-four-22-you-had-one-job/4000-787351/');
    expect($cbam->notes())->toBeString();
    expect($cbam->gtin())->toBeArray();
    expect($cbam->extras())->toBeArray();
    expect($cbam->editors())->toBeArray();
    expect($cbam->scanInformation())->toBeString();
    expect($cbam->communityRating())->toBeNull();
    expect($cbam->isBlackAndWhite())->toBeFalse();

    expect($cbam->review())->toBeNull();
    expect($cbam->alternateSeries())->toBeString();
    expect($cbam->alternateNumber())->toBeNull();
    expect($cbam->alternateCount())->toBeNull();
    expect($cbam->count())->toBeNull();
    expect($cbam->volume())->toBeNull();
    expect($cbam->storyArc())->toBeNull();
    expect($cbam->storyArcNumber())->toBeNull();
    expect($cbam->seriesGroup())->toBeString();

    expect($cbam->mainCharacterOrTeam())->toBeNull();
    expect($cbam->format())->toBe('CBZ');

    $date = new DateTime('2020-10-01');
    expect($cbam->date()->format('Y-m-d'))->toBe($date->format('Y-m-d'));

    expect($cbam->toArray())->toBeArray();
    expect($cbam->toJson())->toBeString();
    expect($cbam->__toString())->toBeString();
})->with([CBZ_CBAM]);

it('can use ComicMeta', function () {
    $meta = new ComicMeta(
        alternateSeries: 'alternate series',
        alternateNumber: 1,
        alternateCount: 'alternate count',
        count: 1,
        volume: 1,
        storyArc: 'story arc',
        storyArcNumber: 1,
        seriesGroup: 'series group',
        imprint: 'imprint',
        scanInformation: 'scan information',
        notes: 'notes',
        communityRating: 10.0,
        isBlackAndWhite: true,
        ageRating: AgeRatingEnum::TEEN,
        review: 'review',
        web: 'web',
        manga: MangaEnum::NO,
        mainCharacterOrTeam: 'main character or team',
        format: 'format',
    );

    $meta->setCharacters(['character 1', 'character 2']);
    $meta->setTeams(['team 1', 'team 2']);
    $meta->setLocations(['location 1', 'location 2']);
    $meta->setEditors(['editor 1', 'editor 2']);

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
    expect($meta->scanInformation())->toBe('scan information');
    expect($meta->notes())->toBe('notes');
    expect($meta->communityRating())->toBe(10.0);
    expect($meta->isBlackAndWhite())->toBeTrue();
    expect($meta->ageRating())->toBe(AgeRatingEnum::TEEN);
    expect($meta->review())->toBe('review');
    expect($meta->web())->toBe('web');
    expect($meta->manga())->toBe(MangaEnum::NO);
    expect($meta->mainCharacterOrTeam())->toBe('main character or team');
    expect($meta->format())->toBe('format');
});
