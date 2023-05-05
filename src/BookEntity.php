<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Ebook\Book\BookCreator;
use Kiwilan\Ebook\Book\BookIdentifier;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;

class BookEntity
{
    protected ?string $title = null;

    protected ?BookCreator $authorFirst = null;

    /** @var BookCreator[] */
    protected array $authors = [];

    protected ?string $description = null;

    protected ?string $contributor = null;

    protected ?string $rights = null;

    protected ?string $publisher = null;

    /** @var BookIdentifier[] */
    protected array $identifiers = [];

    protected ?DateTime $date = null;

    protected ?string $language = null;

    /** @var string[] */
    protected array $tags = [];

    protected ?string $series = null;

    protected ?int $volume = null;

    protected ?float $rating = null;

    protected ?int $pageCount = null;

    /** @var string[]|null */
    protected ?array $editors = null;

    protected ?string $review = null;

    protected ?string $web = null;

    protected ?MangaEnum $manga = null;

    protected bool $isBlackAndWhite = false;

    protected ?AgeRatingEnum $ageRating = null;

    protected ?ComicMeta $comicMeta = null;

    /** @var array<string, mixed> */
    protected array $extras = [];

    protected function __construct(
        protected string $path,
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self($path);

        $self->manga = MangaEnum::NO;
        $self->isBlackAndWhite = false;
        $self->ageRating = AgeRatingEnum::UNKNOWN;

        return $self;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function authorFirst(): ?BookCreator
    {
        return $this->authorFirst;
    }

    /**
     * @return BookCreator[]
     */
    public function authors(): array
    {
        return $this->authors;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function contributor(): ?string
    {
        return $this->contributor;
    }

    public function rights(): ?string
    {
        return $this->rights;
    }

    public function publisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * @return BookIdentifier[]
     */
    public function identifiers(): array
    {
        return $this->identifiers;
    }

    public function date(): ?DateTime
    {
        return $this->date;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags;
    }

    public function series(): ?string
    {
        return $this->series;
    }

    public function volume(): ?int
    {
        return $this->volume;
    }

    public function rating(): ?float
    {
        return $this->rating;
    }

    public function pageCount(): ?int
    {
        return $this->pageCount;
    }

    /**
     * @return string[]|null
     */
    public function editors(): ?array
    {
        return $this->editors;
    }

    public function review(): ?string
    {
        return $this->review;
    }

    public function web(): ?string
    {
        return $this->web;
    }

    public function manga(): ?MangaEnum
    {
        return $this->manga;
    }

    public function isBlackAndWhite(): bool
    {
        return $this->isBlackAndWhite;
    }

    public function ageRating(): ?AgeRatingEnum
    {
        return $this->ageRating;
    }

    public function comicMeta(): ?ComicMeta
    {
        return $this->comicMeta;
    }

    public function extras(): array
    {
        return $this->extras;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setAuthorFirst(?BookCreator $authorFirst): self
    {
        $this->authorFirst = $authorFirst;

        return $this;
    }

    /**
     * @param  BookCreator[]  $authors
     */
    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setContributor(?string $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function setRights(?string $rights): self
    {
        $this->rights = $rights;

        return $this;
    }

    public function setPublisher(?string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @param  BookIdentifier[]  $identifiers
     */
    public function setIdentifiers(array $identifiers): self
    {
        $this->identifiers = $identifiers;

        return $this;
    }

    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param  string[]  $tags
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function setSeries(?string $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function setRating(int|float|null $rating): self
    {
        $this->rating = floatval($rating);

        return $this;
    }

    public function setPageCount(?int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    /**
     * @param  string[]  $editors
     */
    public function setEditors(?array $editors): self
    {
        $this->editors = $editors;

        return $this;
    }

    public function setReview(?string $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function setManga(?MangaEnum $manga): self
    {
        if ($manga === null) {
            $this->manga = MangaEnum::NO;

            return $this;
        }

        $this->manga = $manga;

        return $this;
    }

    public function setIsBlackAndWhite(bool $isBlackAndWhite = true): self
    {
        $this->isBlackAndWhite = $isBlackAndWhite;

        return $this;
    }

    public function setAgeRating(?AgeRatingEnum $ageRating): self
    {
        if ($ageRating === null) {
            $this->ageRating = AgeRatingEnum::UNKNOWN;

            return $this;
        }

        $this->ageRating = $ageRating;

        return $this;
    }

    public function setComicMeta(?ComicMeta $comicMeta): self
    {
        $this->comicMeta = $comicMeta;

        return $this;
    }

    public function setExtras(array $extras): self
    {
        $this->extras = $extras;

        return $this;
    }
}

class ComicMeta
{
    /** @var string[] */
    protected ?array $characters = null;

    /** @var string[] */
    protected ?array $teams = null;

    /** @var string[] */
    protected ?array $locations = null;

    public function __construct(
        protected ?string $alternateSeries = null,
        protected ?int $alternateNumber = null,
        protected ?string $alternateCount = null,
        protected ?int $count = null,
        protected ?int $volume = null,
        protected ?string $storyArc = null,
        protected ?int $storyArcNumber = null,
        protected ?string $seriesGroup = null,
        protected ?string $imprint = null,
    ) {
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

    public function alternateSeries(): ?string
    {
        return $this->alternateSeries;
    }

    public function alternateNumber(): ?int
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

    public function imprint(): ?string
    {
        return $this->imprint;
    }

    /**
     * @param  string[]  $characters
     */
    public function setCharacters(array $characters): self
    {
        $this->characters = $characters;

        return $this;
    }

    /**
     * @param  string[]  $teams
     */
    public function setTeams(array $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @param  string[]  $locations
     */
    public function setLocations(array $locations): self
    {
        $this->locations = $locations;

        return $this;
    }

    public function setAlternateSeries(?string $alternateSeries): self
    {
        $this->alternateSeries = $alternateSeries;

        return $this;
    }

    public function setAlternateNumber(?int $alternateNumber): self
    {
        $this->alternateNumber = $alternateNumber;

        return $this;
    }

    public function setAlternateCount(?string $alternateCount): self
    {
        $this->alternateCount = $alternateCount;

        return $this;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function setStoryArc(?string $storyArc): self
    {
        $this->storyArc = $storyArc;

        return $this;
    }

    public function setStoryArcNumber(?int $storyArcNumber): self
    {
        $this->storyArcNumber = $storyArcNumber;

        return $this;
    }

    public function setSeriesGroup(?string $seriesGroup): self
    {
        $this->seriesGroup = $seriesGroup;

        return $this;
    }

    public function setImprint(?string $imprint): self
    {
        $this->imprint = $imprint;

        return $this;
    }
}
