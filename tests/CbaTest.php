<?php

// CBAM comic-book-archive-metadata with ComicInfo.xml
// CBML comic-book-markup language with ComicBook.xml

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\EbookFormatEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\Ebook\Formats\Cba\Parser\CbamTemplate;
use Kiwilan\Ebook\Tools\ComicMeta;
use Kiwilan\XmlReader\XmlReader;

it('can parse cba', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getFormat())->toBe(EbookFormatEnum::CBA);
    expect($ebook->getpath())->toBe($path);
})->with([...CBA_ITEMS, CBZ_CBAM]);

it('can parse no metadata', function () {
    $ebook = Ebook::read(CBZ_NO_METADATA);

    expect($ebook->hasMetadata())->toBeFalse();
});

it('can parse ComicInfo basic', function () {
    $metadata = XmlReader::make(file_get_contents(COMIC_INFO_BASIC));
    $cba = CbamTemplate::make($metadata);

    expect($cba->getTitle())->toBe('Grise Bouille, Tome I');
    expect($cba->getSeries())->toBe('Grise Bouille');
    expect($cba->getNumber())->toBe(1);
    expect($cba->getWriters())->toBeArray()
        ->toHaveCount(1)
        ->toContain('Simon « Gee » Giraudot');
    expect($cba->getPublisher())->toBe('Des livres en Communs (Framasoft)');
    expect($cba->getLanguage())->toBe('fr');
});

it('can parse ComicInfo basic cba book', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getTitle())->toBe('Grise Bouille, Tome I');
    expect($ebook->getSeries())->toBe('Transmetropolitain');
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors())->toHaveCount(1);
    expect($ebook->getAuthors()[0]->getName())->toBe('Simon « Gee » Giraudot');
    expect($ebook->getPublisher())->toBe('Des livres en Communs (Framasoft)');
    expect($ebook->getLanguage())->toBe('fr');
})->with(CBA_ITEMS);

it('can extract cba cover', function (string $path) {
    $ebook = Ebook::read($path);

    $path = 'tests/output/cover-cba.jpg';
    file_put_contents($path, $ebook->getCover()->getContents());

    expect($ebook->getCover()->getContents())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
    expect(fileIsValidImg($path))->toBeTrue();
})->with([...CBA_ITEMS, CBZ_CBAM]);

it('can parse ComicMeta', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getFormat())->toBe(EbookFormatEnum::CBA);
    expect($ebook->getpath())->toBe($path);
    expect($ebook->hasMetadata())->toBeTrue();

    expect($ebook->getTitle())->toBe('You Had One Job');
    expect($ebook->getSeries())->toBe('Fantastic Four');
    expect($ebook->getVolume())->toBe(22);
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthorMain()->getName())->toBe('Dan Slott');
    expect($ebook->getAuthors())->toHaveCount(12);
    expect($ebook->getAuthors()[0]->getName())->toBe('Dan Slott');
    expect($ebook->getPublisher())->toBe('Marvel');
    expect($ebook->getLanguage())->toBe('en');
    expect($ebook->getDescription())->toBeString();

    $date = new DateTime('2020-10-01');
    expect($ebook->getPublishDate()->format('Y-m-d'))->toBe($date->format('Y-m-d'));
    expect($ebook->getPagesCount())->toBe(24);

    expect($ebook->getExtras())->toBeArray();
    /** @var ComicMeta $comicMeta */
    $comicMeta = $ebook->getExtras()['comicMeta'];

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

it('can parse CbamTemplate', function (string $path) {
    $ebook = Ebook::read($path);
    $cbam = $ebook->getMetadata()->getCba()?->getCbam();

    if (! $cbam) {
        throw new Exception('CBAM is null');
    }

    expect($cbam)->toBeInstanceOf(CbamTemplate::class);
    expect($cbam->getTitle())->toBe('You Had One Job');
    expect($cbam->getSeries())->toBe('Fantastic Four');
    expect($cbam->getNumber())->toBe(22);
    expect($cbam->getWriters())->toBeArray();
    expect($cbam->getWriters())->toHaveCount(1);
    expect($cbam->getWriters()[0])->toBe('Dan Slott');
    expect($cbam->getPublisher())->toBe('Marvel');
    expect($cbam->getLanguage())->toBe('en');
    expect($cbam->getSummary())->toBeString();

    expect($cbam->getPencillers())->toBeArray();
    expect($cbam->getInkers())->toBeArray();
    expect($cbam->getColorists())->toBeArray();
    expect($cbam->getLetterers())->toBeArray();
    expect($cbam->getCoverArtists())->toBeArray();
    expect($cbam->getTranslators())->toBeArray();

    expect($cbam->getGenres())->toBeArray();
    expect($cbam->getGenres())->toHaveCount(1);

    expect($cbam->getCharacters())->toBeArray();
    expect($cbam->getTeams())->toBeArray();
    expect($cbam->getLocations())->toBeArray();

    expect($cbam->getAlternateSeries())->toBe('Empyre');
    expect($cbam->getSeriesGroup())->toBe('Fantastic Four');
    expect($cbam->getAgeRating())->toBe(AgeRatingEnum::TEEN);
    expect($cbam->getManga())->toBe(MangaEnum::NO);
    expect($cbam->getPageCount())->toBe(24);
    expect($cbam->getImprint())->toBe('Vertigo');
    expect($cbam->getWeb())->toBe('https://comicvine.gamespot.com/fantastic-four-22-you-had-one-job/4000-787351/');
    expect($cbam->getNotes())->toBeString();
    expect($cbam->getGtin())->toBeArray();
    expect($cbam->getExtras())->toBeArray();
    expect($cbam->getEditors())->toBeArray();
    expect($cbam->getScanInformation())->toBeString();
    expect($cbam->getCommunityRating())->toBeNull();
    expect($cbam->isBlackAndWhite())->toBeFalse();

    expect($cbam->getReview())->toBeNull();
    expect($cbam->getAlternateSeries())->toBeString();
    expect($cbam->getAlternateNumber())->toBeNull();
    expect($cbam->getAlternateCount())->toBeNull();
    expect($cbam->getCount())->toBeNull();
    expect($cbam->getVolume())->toBeNull();
    expect($cbam->getStoryArc())->toBeNull();
    expect($cbam->getStoryArcNumber())->toBeNull();
    expect($cbam->getSeriesGroup())->toBeString();

    expect($cbam->getMainCharacterOrTeam())->toBeNull();
    expect($cbam->getFormat())->toBe('CBZ');

    $date = new DateTime('2020-10-01');
    expect($cbam->getDate()->format('Y-m-d'))->toBe($date->format('Y-m-d'));

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
