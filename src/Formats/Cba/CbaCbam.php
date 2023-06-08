<?php

namespace Kiwilan\Ebook\Formats\Cba;

use DateTime;
use Kiwilan\Ebook\EbookCore;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\ComicMeta;

/**
 * @docs https://anansi-project.github.io/docs/comicinfo/schemas/v2.0
 */
class CbaCbam extends CbaBase
{
    protected string $metadataFilename = 'ComicInfo.xml';

    protected function __construct(
        protected array $metadata,
    ) {
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public static function make(array $metadata): self
    {
        $self = new self($metadata);
        $self->parse();

        return $self;
    }

    public function toCore(): EbookCore
    {
        $core = EbookCore::make();
        $writers = $this->arrayableToCreators($this->writers, 'writer');

        $core->setTitle($this->title);
        if (array_key_exists(0, $writers)) {
            $core->setAuthorMain($writers[0]);
        }

        $core->setAuthors([
            ...$writers,
            ...$this->arrayableToCreators($this->pencillers, 'penciller'),
            ...$this->arrayableToCreators($this->inkers, 'inker'),
            ...$this->arrayableToCreators($this->colorists, 'colorist'),
            ...$this->arrayableToCreators($this->letterers, 'letterer'),
            ...$this->arrayableToCreators($this->coverArtists, 'cover artist'),
            ...$this->arrayableToCreators($this->translators, 'translator'),
        ]);
        $core->setDescription($this->summary);
        // $core->setContributor($this->scanInformation);
        // $core->setRights($this->notes);
        $core->setPublisher($this->publisher);
        //$core->setIdentifiers();
        $core->setPublishDate($this->date);
        $core->setLanguage($this->language);
        $core->setTags($this->genres);
        $core->setSeries($this->series);
        $core->setVolume($this->number);
        // $core->setRating($this->communityRating);
        // $core->setPageCount($this->pageCount);
        // $core->setEditors($this->editors);
        // $core->setReview($this->review);
        // $core->setWeb($this->web);
        // $core->setManga($this->manga);
        // $core->setIsBlackAndWhite($this->isBlackAndWhite);
        // $core->setAgeRating($this->ageRating);

        $comicMeta = new ComicMeta(
            alternateSeries: $this->alternateSeries,
            alternateNumber: $this->alternateNumber,
            alternateCount: $this->alternateCount,
            count: $this->count,
            volume: $this->volume,
            storyArc: $this->storyArc,
            storyArcNumber: $this->storyArcNumber,
            seriesGroup: $this->seriesGroup,
            imprint: $this->imprint,
        );
        $comicMeta->setCharacters($this->characters);
        $comicMeta->setTeams($this->teams);
        $comicMeta->setLocations($this->locations);

        // $core->setComicMeta($comicMeta);

        // $core->setExtras([
        //     ...$this->extras,
        //     'mainCharacterOrTeam' => $this->mainCharacterOrTeam,
        //     'format' => $this->format,
        // ]);

        return $core;
    }

    /**
     * @return string[]
     */
    private function arrayable(?string $value): array
    {
        if (! $value) {
            return [];
        }

        $value = trim($value);
        $value = str_replace(';', ',', $value);
        $value = explode(',', $value);

        return array_map(fn ($v) => trim($v), $value);
    }

    private function arrayableToCreators(array $core, ?string $role = null): array
    {
        if (empty($core)) {
            return [];
        }

        $creators = [];
        foreach ($core as $item) {
            $creators[] = new BookAuthor($item, $role);
        }

        return $creators;
    }

    private function parse(): void
    {
        $this->title = $this->extract('Title');
        $this->series = $this->extract('Series');
        $this->number = $this->extractInt('Number');
        $this->count = $this->extractInt('Count');
        $this->volume = $this->extractInt('Volume');
        $this->alternateSeries = $this->extract('AlternateSeries');
        $this->alternateNumber = $this->extractInt('AlternateNumber');
        $this->alternateCount = $this->extract('AlternateCount');
        $this->summary = $this->extract('Summary');
        $this->notes = $this->extract('Notes');
        $this->extras['year'] = $this->extractInt('Year');
        $this->extras['month'] = $this->extractInt('Month');
        $this->extras['day'] = $this->extractInt('Day');

        $year = $this->extras['year'] ?? null;
        $month = $this->extras['month'] ?? '01';
        $day = $this->extras['day'] ?? '01';

        if ($year) {
            $date = "{$year}-{$month}-{$day}";
            $this->date = new DateTime($date);
        }

        $this->writers = $this->arrayable($this->extract('Writer'));
        $this->pencillers = $this->arrayable($this->extract('Penciller'));
        $this->inkers = $this->arrayable($this->extract('Inker'));
        $this->colorists = $this->arrayable($this->extract('Colorist'));
        $this->letterers = $this->arrayable($this->extract('Letterer'));
        $this->coverArtists = $this->arrayable($this->extract('CoverArtist'));
        $this->translators = $this->arrayable($this->extract('Translator'));
        $this->editors = $this->arrayable($this->extract('Editor'));
        $this->publisher = $this->extract('Publisher');
        $this->imprint = $this->extract('Imprint');
        $this->genres = $this->arrayable($this->extract('Genre'));
        $this->web = $this->extract('Web');
        $this->pageCount = $this->extractInt('PageCount');
        $this->language = $this->extract('LanguageISO');
        $this->format = $this->extract('Format');
        $this->isBlackAndWhite = $this->extract('BlackAndWhite') === 'Yes';

        $manga = $this->extract('Manga');
        $this->manga = $manga ? MangaEnum::tryFrom($manga) : null;

        $this->characters = $this->arrayable($this->extract('Characters'));
        $this->teams = $this->arrayable($this->extract('Teams'));
        $this->locations = $this->arrayable($this->extract('Locations'));
        $this->scanInformation = $this->extract('ScanInformation');
        $this->storyArc = $this->extract('StoryArc');
        $this->storyArcNumber = $this->extractInt('StoryArcNumber');
        $this->seriesGroup = $this->extract('SeriesGroup');

        $ageRating = $this->extract('AgeRating');
        $this->ageRating = $ageRating ? AgeRatingEnum::tryFrom($ageRating) : AgeRatingEnum::UNKNOWN;

        $communityRating = $this->extract('CommunityRating');
        $this->communityRating = $communityRating ? (float) $communityRating : null;

        $this->mainCharacterOrTeam = $this->extract('MainCharacterOrTeam');
        $this->review = $this->extract('Review');

        $pages = $this->metadata['Pages'] ?? null;

        if ($pages && array_key_exists('Page', $pages)) {
            $pages = $pages['Page'];

            $items = [];
            foreach ($pages as $page) {
                if (array_key_exists('@attributes', $page)) {
                    $items[] = $page['@attributes'];
                }
            }

            $this->extras['pages'] = $items;
        }
    }

    private function extract(string $key): ?string
    {
        $string = $this->metadata[$key] ?? null;

        if (! $string) {
            return null;
        }

        return $this->normalizeString($string);
    }

    private function extractInt(string $key): ?int
    {
        if ($this->extract($key)) {
            return (int) $this->extract($key);
        }

        return null;
    }

    private function normalizeString(string $string): ?string
    {
        if (empty($string)) {
            return null;
        }

        $string = preg_replace('/\s+/', ' ', $string);
        $string = preg_replace("/\r|\n/", '', $string);
        $string = trim($string);

        return $string;
    }
}