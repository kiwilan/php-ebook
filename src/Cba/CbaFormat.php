<?php

namespace Kiwilan\Ebook\Cba;

use DateTime;
use Kiwilan\Ebook\BookEntity;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;

abstract class CbaFormat
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

    protected ?string $editor = null;

    protected ?string $publisher = null;

    protected ?string $imprint = null;

    protected ?float $communityRating = null; // min: 0; max: 5; digits: 2

    protected bool $isBlackAndWhite = false;

    protected ?MangaEnum $manga = null;

    protected ?AgeRatingEnum $ageRating = null;

    protected ?string $review = null;

    protected ?string $mainCharacterOrTeam = null;

    protected ?string $alternateSeries = null;

    protected ?string $alternateNumber = null;

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

    protected function __construct(

    ) {
    }

    abstract public static function create(array $metadata): self;

    abstract public function toBook(string $path): BookEntity;

    /**
     * @return string[]
     */
    public function writers(): array
    {
        return $this->writers;
    }

    /**
     * @return string[]
     */
    public function pencillers(): array
    {
        return $this->pencillers;
    }

    /**
     * @return string[]
     */
    public function inkers(): array
    {
        return $this->inkers;
    }

    /**
     * @return string[]
     */
    public function colorists(): array
    {
        return $this->colorists;
    }

    /**
     * @return string[]
     */
    public function letterers(): array
    {
        return $this->letterers;
    }

    /**
     * @return string[]
     */
    public function coverArtists(): array
    {
        return $this->coverArtists;
    }

    /**
     * @return string[]
     */
    public function translators(): array
    {
        return $this->translators;
    }

    /**
     * @return string[]
     */
    public function genres(): array
    {
        return $this->genres;
    }

    /**
     * @return string[]
     */
    public function characters(): array
    {
        return $this->characters;
    }

    /**
     * @return string[]
     */
    public function teams(): array
    {
        return $this->teams;
    }

    /**
     * @return string[]
     */
    public function locations(): array
    {
        return $this->locations;
    }

    /**
     * @return string[]
     */
    public function gtin(): array
    {
        return $this->gtin;
    }

    /**
     * @return array<string, string>
     */
    public function extras(): array
    {
        return $this->extras;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function series(): ?string
    {
        return $this->series;
    }

    public function number(): ?int
    {
        return $this->number;
    }

    public function summary(): ?string
    {
        return $this->summary;
    }

    public function date(): ?DateTime
    {
        return $this->date;
    }

    public function pageCount(): ?int
    {
        return $this->pageCount;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function editor(): ?string
    {
        return $this->editor;
    }

    public function publisher(): ?string
    {
        return $this->publisher;
    }

    public function imprint(): ?string
    {
        return $this->imprint;
    }

    public function communityRating(): ?float
    {
        return $this->communityRating;
    }

    public function isBlackAndWhite(): bool
    {
        return $this->isBlackAndWhite;
    }

    public function manga(): ?MangaEnum
    {
        return $this->manga;
    }

    public function ageRating(): ?AgeRatingEnum
    {
        return $this->ageRating;
    }

    public function review(): ?string
    {
        return $this->review;
    }

    public function mainCharacterOrTeam(): ?string
    {
        return $this->mainCharacterOrTeam;
    }

    public function alternateSeries(): ?string
    {
        return $this->alternateSeries;
    }

    public function alternateNumber(): ?string
    {
        return $this->alternateNumber;
    }

    public function alternateCount(): ?string
    {
        return $this->alternateCount;
    }

    public function count(): ?int
    {
        return $this->count;
    }

    public function volume(): ?int
    {
        return $this->volume;
    }

    public function storyArc(): ?string
    {
        return $this->storyArc;
    }

    public function storyArcNumber(): ?int
    {
        return $this->storyArcNumber;
    }

    public function seriesGroup(): ?string
    {
        return $this->seriesGroup;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function scanInformation(): ?string
    {
        return $this->scanInformation;
    }

    public function web(): ?string
    {
        return $this->web;
    }

    public function format(): ?string
    {
        return $this->format;
    }
}
