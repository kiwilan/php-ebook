<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Ebook\Book\BookCreator;
use Kiwilan\Ebook\Book\BookIdentifier;
use Kiwilan\Ebook\Epub\EpubOpf;

class BookEntity
{
    protected ?string $title = null;

    /** @var BookCreator[] */
    protected array $authors = [];

    protected ?string $description = null;

    protected ?string $contributor = null;

    protected ?string $rights = null;

    protected ?string $publisher = null;

    /** @var BookIdentifier[] */
    protected array $identifiers = [];

    protected ?string $identifierGoogle = null;

    protected ?string $identifierAmazon = null;

    protected ?string $identifierIsbn10 = null;

    protected ?string $identifierIsbn13 = null;

    protected ?DateTime $date = null;

    protected ?string $language = null;

    /** @var string[] */
    protected array $tags = [];

    protected ?string $series = null;

    protected ?int $volume = null;

    protected ?int $rating = null;

    protected ?int $pageCount = null;

    protected ?string $cover = null;

    /** @var string[] */
    protected array $extras = [];

    protected function __construct(
        protected string $path,
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self($path);

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

    public function identifierGoogle(): ?string
    {
        return $this->identifierGoogle;
    }

    public function identifierAmazon(): ?string
    {
        return $this->identifierAmazon;
    }

    public function identifierIsbn10(): ?string
    {
        return $this->identifierIsbn10;
    }

    public function identifierIsbn13(): ?string
    {
        return $this->identifierIsbn13;
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

    public function rating(): ?int
    {
        return $this->rating;
    }

    public function pageCount(): ?int
    {
        return $this->pageCount;
    }

    public function cover(): ?string
    {
        return $this->cover;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function setIdentifierGoogle(?string $identifierGoogle): self
    {
        $this->identifierGoogle = $identifierGoogle;

        return $this;
    }

    public function setIdentifierAmazon(?string $identifierAmazon): self
    {
        $this->identifierAmazon = $identifierAmazon;

        return $this;
    }

    public function setIdentifierIsbn10(?string $identifierIsbn10): self
    {
        $this->identifierIsbn10 = $identifierIsbn10;

        return $this;
    }

    public function setIdentifierIsbn13(?string $identifierIsbn13): self
    {
        $this->identifierIsbn13 = $identifierIsbn13;

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

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function setPageCount(?int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function convertFromOpdf(EpubOpf $opf): self
    {
        $this->title = $opf->dcTitle();
        $this->authors = array_values($opf->dcCreators());
        $this->description = strip_tags($opf->dcDescription());
        $this->contributor = ! empty($opf->dcContributors()) ? implode(', ', $opf->dcContributors()) : null;
        $this->rights = ! empty($opf->dcRights()) ? implode(', ', $opf->dcRights()) : null;
        $this->publisher = $opf->dcPublisher();
        $this->identifiers = $opf->dcIdentifiers();

        if (! empty($opf->dcIdentifiers())) {
            foreach ($opf->dcIdentifiers() as $identifier) {
                if ($identifier->type() === 'google') {
                    $this->identifierGoogle = $identifier->content();
                }
                if ($identifier->type() === 'amazon') {
                    $this->identifierAmazon = $identifier->content();
                }
                if ($identifier->type() === 'isbn10') {
                    $this->identifierIsbn10 = $identifier->content();
                }
                if ($identifier->type() === 'isbn13') {
                    $this->identifierIsbn13 = $identifier->content();
                }
            }
        }

        $this->date = $opf->dcDate();
        $this->language = $opf->dcLanguage();

        if (! empty($opf->dcSubject())) {
            foreach ($opf->dcSubject() as $subject) {
                if (strlen($subject) < 50) {
                    $this->tags[] = $subject;
                }
            }
        }

        if (! empty($opf->meta())) {
            foreach ($opf->meta() as $meta) {
                if ($meta->name() === 'calibre:series') {
                    $this->series = $meta->content();
                }
                if ($meta->name() === 'calibre:series_index') {
                    $this->volume = (int) $meta->content();
                }
                if ($meta->name() === 'calibre:rating') {
                    $this->rating = (int) $meta->content();
                }
            }
        }

        return $this;
    }
}
