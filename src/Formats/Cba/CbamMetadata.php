<?php

namespace Kiwilan\Ebook\Formats\Cba;

use DateTime;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\XmlReader\XmlReader;

/**
 * @docs https://anansi-project.github.io/docs/comicinfo/schemas/v2.0
 */
class CbamMetadata extends CbaTemplate
{
    /** @var string[] */
    protected array $writers = [];

    /** @var string[] */
    protected array $pencillers = [];

    /** @var string[] */
    protected array $inkers = [];

    /** @var string[] */
    protected array $colorists = [];

    /** @var string[] */
    protected array $letterers = [];

    /** @var string[] */
    protected array $coverArtists = [];

    /** @var string[] */
    protected array $translators = [];

    /** @var string[] */
    protected array $genres = [];

    /** @var string[] */
    protected array $characters = [];

    /** @var string[] */
    protected array $teams = [];

    /** @var string[] */
    protected array $locations = [];

    /** @var string[] */
    protected array $gtin = [];

    /** @var array<string, string> */
    protected array $extras = [];

    protected ?string $title = null;

    protected ?string $series = null;

    protected ?int $number = null; // Number of the book in the series

    protected ?string $summary = null;

    protected ?DateTime $date = null;

    protected ?int $pageCount = null;

    protected ?string $language = null;

    /** @var string[] */
    protected array $editors = [];

    protected ?string $publisher = null;

    protected ?string $imprint = null;

    protected ?float $communityRating = null; // min: 0; max: 5; digits: 2

    protected bool $isBlackAndWhite = false;

    protected ?MangaEnum $manga = null;

    protected ?AgeRatingEnum $ageRating = null;

    protected ?string $review = null;

    protected ?string $mainCharacterOrTeam = null;

    protected ?string $alternateSeries = null;

    protected ?int $alternateNumber = null;

    protected ?string $alternateCount = null;

    protected ?int $count = null; // The total number of books in the series

    protected ?int $volume = null; // Volume containing the book. Only US Comics

    protected ?string $storyArc = null;

    protected ?int $storyArcNumber = null;

    protected ?string $seriesGroup = null;

    protected ?string $notes = null;

    protected ?string $scanInformation = null;

    protected ?string $web = null;

    protected ?string $format = null;

    protected string $metadataFilename = 'ComicInfo.xml';

    protected function __construct(
        protected XmlReader $xml,
    ) {
    }

    public static function make(XmlReader $xml): self
    {
        $self = new self($xml);
        $self->parse();

        return $self;
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

        $pages = $this->xml->find('Pages');

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
        $string = $this->xml->find($key);

        if (! $string) {
            return null;
        }

        if (is_array($string)) {
            $string = XmlReader::parseContent($string);
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

    /**
     * @return string[]
     */
    public function getWriters(): array
    {
        return $this->writers;
    }

    /**
     * @return string[]
     */
    public function getPencillers(): array
    {
        return $this->pencillers;
    }

    /**
     * @return string[]
     */
    public function getInkers(): array
    {
        return $this->inkers;
    }

    /**
     * @return string[]
     */
    public function getColorists(): array
    {
        return $this->colorists;
    }

    /**
     * @return string[]
     */
    public function getLetterers(): array
    {
        return $this->letterers;
    }

    /**
     * @return string[]
     */
    public function getCoverArtists(): array
    {
        return $this->coverArtists;
    }

    /**
     * @return string[]
     */
    public function getTranslators(): array
    {
        return $this->translators;
    }

    /**
     * @return string[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }

    /**
     * @return string[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return string[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @return string[]
     */
    public function getGtin(): array
    {
        return $this->gtin;
    }

    /**
     * @return array<string, string>
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return string[]
     */
    public function getEditors(): array
    {
        return $this->editors;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function getImprint(): ?string
    {
        return $this->imprint;
    }

    public function getCommunityRating(): ?float
    {
        return $this->communityRating;
    }

    public function isBlackAndWhite(): bool
    {
        return $this->isBlackAndWhite;
    }

    public function getManga(): ?MangaEnum
    {
        return $this->manga;
    }

    public function getAgeRating(): ?AgeRatingEnum
    {
        return $this->ageRating;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function getMainCharacterOrTeam(): ?string
    {
        return $this->mainCharacterOrTeam;
    }

    public function getAlternateSeries(): ?string
    {
        return $this->alternateSeries;
    }

    public function getAlternateNumber(): ?int
    {
        return $this->alternateNumber;
    }

    public function getAlternateCount(): ?string
    {
        return $this->alternateCount;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function getStoryArc(): ?string
    {
        return $this->storyArc;
    }

    public function getStoryArcNumber(): ?int
    {
        return $this->storyArcNumber;
    }

    public function getSeriesGroup(): ?string
    {
        return $this->seriesGroup;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getScanInformation(): ?string
    {
        return $this->scanInformation;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'series' => $this->series,
            'number' => $this->number,
            'summary' => $this->summary,
            'date' => $this->date,
            'pageCount' => $this->pageCount,
            'language' => $this->language,
            'editors' => $this->editors,
            'publisher' => $this->publisher,
            'imprint' => $this->imprint,
            'communityRating' => $this->communityRating,
            'isBlackAndWhite' => $this->isBlackAndWhite,
            'manga' => $this->manga,
            'ageRating' => $this->ageRating,
            'review' => $this->review,
            'mainCharacterOrTeam' => $this->mainCharacterOrTeam,
            'alternateSeries' => $this->alternateSeries,
            'alternateNumber' => $this->alternateNumber,
            'alternateCount' => $this->alternateCount,
            'count' => $this->count,
            'volume' => $this->volume,
            'storyArc' => $this->storyArc,
            'storyArcNumber' => $this->storyArcNumber,
            'seriesGroup' => $this->seriesGroup,
            'notes' => $this->notes,
            'scanInformation' => $this->scanInformation,
            'web' => $this->web,
            'format' => $this->format,
            'writers' => $this->writers,
            'pencillers' => $this->pencillers,
            'inkers' => $this->inkers,
            'colorists' => $this->colorists,
            'letterers' => $this->letterers,
            'coverArtists' => $this->coverArtists,
            'translators' => $this->translators,
            'genres' => $this->genres,
            'characters' => $this->characters,
            'teams' => $this->teams,
            'locations' => $this->locations,
            'gtin' => $this->gtin,
            'extras' => $this->extras,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return "{$this->title} ({$this->series} #{$this->number})";
    }
}
