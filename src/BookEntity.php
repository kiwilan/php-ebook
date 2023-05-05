<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Ebook\Book\BookCreator;
use Kiwilan\Ebook\Book\BookIdentifier;
use Kiwilan\Ebook\Entity\ComicMeta;
use Kiwilan\Ebook\Entity\MetaTitle;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;

class BookEntity
{
    protected ?string $title = null;

    protected ?MetaTitle $metaTitle = null;

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

    protected ?int $words = null;

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
    ) {
    }

    public static function make(): self
    {
        $self = new self();

        $self->manga = MangaEnum::UNKNOWN;
        $self->isBlackAndWhite = false;
        $self->ageRating = AgeRatingEnum::UNKNOWN;

        return $self;
    }

    /**
     * Title of the book.
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * Title metadata of the book with slug, sort title, series slug, etc.
     */
    public function metaTitle(): ?MetaTitle
    {
        return $this->metaTitle;
    }

    /**
     * First author of the book (useful if you need to display only one author).
     */
    public function authorFirst(): ?BookCreator
    {
        return $this->authorFirst;
    }

    /**
     * All authors of the book.
     *
     * @return BookCreator[]
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * Description of the book.
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Contributor of the book, from Calibre (EPUB) or scan information (CBA).
     */
    public function contributor(): ?string
    {
        return $this->contributor;
    }

    /**
     * Rights of the book, from Calibre (EPUB) or notes (CBA).
     */
    public function rights(): ?string
    {
        return $this->rights;
    }

    /**
     * Publisher of the book.
     */
    public function publisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * Identifiers of the book.
     *
     * @return BookIdentifier[]
     */
    public function identifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * Publish date of the book.
     */
    public function date(): ?DateTime
    {
        return $this->date;
    }

    /**
     * Language of the book.
     */
    public function language(): ?string
    {
        return $this->language;
    }

    /**
     * Tags of the book.
     *
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags;
    }

    /**
     * Series of the book.
     */
    public function series(): ?string
    {
        return $this->series;
    }

    /**
     * Volume of the book.
     */
    public function volume(): ?int
    {
        return $this->volume;
    }

    /**
     * Rating of the book.
     */
    public function rating(): ?float
    {
        return $this->rating;
    }

    /**
     * Page count of the book.
     */
    public function pageCount(): ?int
    {
        return $this->pageCount;
    }

    /**
     * Words count of the book (only EPUB)
     */
    public function words(): ?int
    {
        return $this->words;
    }

    /**
     * Editors of the book (only CBA).
     *
     * @return string[]|null
     */
    public function editors(): ?array
    {
        return $this->editors;
    }

    /**
     * Review of the book (only CBA).
     */
    public function review(): ?string
    {
        return $this->review;
    }

    /**
     * Web of the book (only CBA).
     */
    public function web(): ?string
    {
        return $this->web;
    }

    /**
     * Manga status of the book (only CBA).
     */
    public function manga(): ?MangaEnum
    {
        return $this->manga;
    }

    /**
     * Is the book black and white (only CBA).
     */
    public function isBlackAndWhite(): bool
    {
        return $this->isBlackAndWhite;
    }

    /**
     * Age rating of the book (only CBA).
     */
    public function ageRating(): ?AgeRatingEnum
    {
        return $this->ageRating;
    }

    /**
     * Comic metadata of the book (only CBA).
     */
    public function comicMeta(): ?ComicMeta
    {
        return $this->comicMeta;
    }

    /**
     * Extras of the book (only CBA).
     *
     * @return array<string, mixed>
     */
    public function extras(): array
    {
        return $this->extras;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setMetaTitle(Ebook $ebook): self
    {
        $this->metaTitle = MetaTitle::make($ebook);

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

    public function setWords(?int $words): self
    {
        $this->words = $words;

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

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authorFirst' => $this->authorFirst?->toArray(),
            'authors' => array_map(fn (BookCreator $creator) => $creator->toArray(), $this->authors),
            'description' => $this->description,
            'contributor' => $this->contributor,
            'rights' => $this->rights,
            'publisher' => $this->publisher,
            'identifiers' => array_map(fn (BookIdentifier $identifier) => $identifier->toArray(), $this->identifiers),
            'date' => $this->date?->format('Y-m-d H:i:s'),
            'language' => $this->language,
            'tags' => $this->tags,
            'series' => $this->series,
            'volume' => $this->volume,
            'rating' => $this->rating,
            'pageCount' => $this->pageCount,
            'words' => $this->words,
            'editors' => $this->editors,
            'review' => $this->review,
            'web' => $this->web,
            'manga' => $this->manga,
            'isBlackAndWhite' => $this->isBlackAndWhite,
            'ageRating' => $this->ageRating,
            'comicMeta' => $this->comicMeta,
            'extras' => $this->extras,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        $authors = array_map(fn (BookCreator $author) => $author->name(), $this->authors);
        $authors = implode(', ', $authors);

        return "{$this->title} by {$authors}";
    }
}
