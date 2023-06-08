<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Ebook\Enums\AgeRatingEnum;
use Kiwilan\Ebook\Enums\MangaEnum;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;
use Kiwilan\Ebook\Tools\ComicMeta;
use Kiwilan\Ebook\Tools\MetaTitle;

/**
 * Data of an ebook, group common data between all formats, for specific data see `metadata` property of `Ebook`.
 */
class EbookCore
{
    protected ?string $title = null;

    protected ?MetaTitle $metaTitle = null;

    protected ?BookAuthor $authorMain = null;

    /** @var BookAuthor[] */
    protected array $authors = [];

    protected ?string $description = null;

    // protected ?string $contributor = null;

    // protected ?string $rights = null;

    protected ?string $publisher = null;

    /** @var BookIdentifier[] */
    protected array $identifiers = [];

    protected ?DateTime $publishDate = null;

    protected ?string $language = null;

    /** @var string[] */
    protected array $tags = [];

    protected ?string $series = null;

    protected ?int $volume = null;

    // protected ?float $rating = null;

    // protected ?int $pageCount = null;

    // protected ?int $wordsCount = null;

    // /** @var string[]|null */
    // protected ?array $editors = null;

    // protected ?string $review = null;

    // protected ?string $web = null;

    // protected ?MangaEnum $manga = null;

    // protected bool $isBlackAndWhite = false;

    // protected ?AgeRatingEnum $ageRating = null;

    // protected ?ComicMeta $comicMeta = null;

    // /** @var array<string, mixed> */
    // protected array $extras = [];

    public static function make(): self
    {
        $self = new self();

        // $self->manga = MangaEnum::UNKNOWN;
        // $self->isBlackAndWhite = false;
        // $self->ageRating = AgeRatingEnum::UNKNOWN;

        return $self;
    }

    // /**
    //  * Contributor of the book, from Calibre (EPUB) or scan information (CBA).
    //  */
    // public function contributor(): ?string
    // {
    //     return $this->contributor;
    // }

    // /**
    //  * Rights of the book, from Calibre (EPUB) or notes (CBA).
    //  */
    // public function rights(): ?string
    // {
    //     return $this->rights;
    // }

    // /**
    //  * Rating of the book.
    //  */
    // public function rating(): ?float
    // {
    //     return $this->rating;
    // }

    // /**
    //  * Page count of the book.
    //  */
    // public function pageCount(): ?int
    // {
    //     return $this->pageCount;
    // }

    // /**
    //  * Words count of the book (only EPUB)
    //  */
    // public function wordsCount(): ?int
    // {
    //     return $this->wordsCount;
    // }

    // /**
    //  * Editors of the book (only CBA).
    //  *
    //  * @return string[]|null
    //  */
    // public function editors(): ?array
    // {
    //     return $this->editors;
    // }

    // /**
    //  * Review of the book (only CBA).
    //  */
    // public function review(): ?string
    // {
    //     return $this->review;
    // }

    // /**
    //  * Web of the book (only CBA).
    //  */
    // public function web(): ?string
    // {
    //     return $this->web;
    // }

    // /**
    //  * Manga status of the book (only CBA).
    //  */
    // public function manga(): ?MangaEnum
    // {
    //     return $this->manga;
    // }

    // /**
    //  * Is the book black and white (only CBA).
    //  */
    // public function isBlackAndWhite(): bool
    // {
    //     return $this->isBlackAndWhite;
    // }

    // /**
    //  * Age rating of the book (only CBA).
    //  */
    // public function ageRating(): ?AgeRatingEnum
    // {
    //     return $this->ageRating;
    // }

    // /**
    //  * Comic metadata of the book (only CBA).
    //  */
    // public function comicMeta(): ?ComicMeta
    // {
    //     return $this->comicMeta;
    // }

    // /**
    //  * Extras of the book (only CBA).
    //  *
    //  * @return array<string, mixed>
    //  */
    // public function extras(): array
    // {
    //     return $this->extras;
    // }

    // public function setContributor(?string $contributor): self
    // {
    //     $this->contributor = $contributor;

    //     return $this;
    // }

    // public function setRights(?string $rights): self
    // {
    //     $this->rights = $rights;

    //     return $this;
    // }

    // public function setRating(int|float|null $rating): self
    // {
    //     $this->rating = floatval($rating);

    //     return $this;
    // }

    // public function setPageCount(?int $pageCount): self
    // {
    //     $this->pageCount = $pageCount;

    //     return $this;
    // }

    // public function setWordsCount(?int $wordsCount): self
    // {
    //     $this->wordsCount = $wordsCount;

    //     return $this;
    // }

    // /**
    //  * @param  string[]  $editors
    //  */
    // public function setEditors(?array $editors): self
    // {
    //     $this->editors = $editors;

    //     return $this;
    // }

    // public function setReview(?string $review): self
    // {
    //     $this->review = $review;

    //     return $this;
    // }

    // public function setWeb(?string $web): self
    // {
    //     $this->web = $web;

    //     return $this;
    // }

    // public function setManga(?MangaEnum $manga): self
    // {
    //     if ($manga === null) {
    //         $this->manga = MangaEnum::NO;

    //         return $this;
    //     }

    //     $this->manga = $manga;

    //     return $this;
    // }

    // public function setIsBlackAndWhite(bool $isBlackAndWhite = true): self
    // {
    //     $this->isBlackAndWhite = $isBlackAndWhite;

    //     return $this;
    // }

    // public function setAgeRating(?AgeRatingEnum $ageRating): self
    // {
    //     if ($ageRating === null) {
    //         $this->ageRating = AgeRatingEnum::UNKNOWN;

    //         return $this;
    //     }

    //     $this->ageRating = $ageRating;

    //     return $this;
    // }

    // public function setComicMeta(?ComicMeta $comicMeta): self
    // {
    //     $this->comicMeta = $comicMeta;

    //     return $this;
    // }

    // public function setExtras(array $extras): self
    // {
    //     $this->extras = $extras;

    //     return $this;
    // }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authorMain' => $this->authorMain?->name(),
            'authors' => array_map(fn (BookAuthor $author) => $author->name(), $this->authors),
            'description' => $this->description,
            // 'contributor' => $this->contributor,
            // 'rights' => $this->rights,
            'publisher' => $this->publisher,
            'identifiers' => array_map(fn (BookIdentifier $identifier) => $identifier->toArray(), $this->identifiers),
            'date' => $this->publishDate?->format('Y-m-d H:i:s'),
            'language' => $this->language,
            'tags' => $this->tags,
            'series' => $this->series,
            'volume' => $this->volume,
            // 'rating' => $this->rating,
            // 'pageCount' => $this->pageCount,
            // 'wordsCount' => $this->wordsCount,
            // 'editors' => $this->editors,
            // 'review' => $this->review,
            // 'web' => $this->web,
            // 'manga' => $this->manga,
            // 'isBlackAndWhite' => $this->isBlackAndWhite,
            // 'ageRating' => $this->ageRating,
            // 'comicMeta' => $this->comicMeta,
            // 'extras' => $this->extras,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        $authors = array_map(fn (BookAuthor $author) => $author->name(), $this->authors);
        $authors = implode(', ', $authors);

        return "{$this->title} by {$authors}";
    }
}
