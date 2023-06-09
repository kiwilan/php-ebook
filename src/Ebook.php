<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Archive\Archive;
use Kiwilan\Archive\Readers\BaseArchive;
use Kiwilan\Ebook\Enums\EbookFormatEnum;
use Kiwilan\Ebook\Formats\Cba\CbaMetadata;
use Kiwilan\Ebook\Formats\EbookMetadata;
use Kiwilan\Ebook\Formats\Epub\EpubMetadata;
use Kiwilan\Ebook\Formats\Pdf\PdfMetadata;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;
use Kiwilan\Ebook\Tools\MetaTitle;

class Ebook
{
    protected ?string $title = null;

    protected ?MetaTitle $metaTitle = null;

    protected ?BookAuthor $authorMain = null;

    /** @var BookAuthor[] */
    protected array $authors = [];

    protected ?string $description = null;

    protected ?string $publisher = null;

    /** @var BookIdentifier[] */
    protected array $identifiers = [];

    protected ?DateTime $publishDate = null;

    protected ?string $language = null;

    /** @var string[] */
    protected array $tags = [];

    protected ?string $series = null;

    protected ?int $volume = null;

    protected ?string $copyright = null;

    protected ?EbookMetadata $metadata = null;

    protected ?EbookFormatEnum $format = null;

    protected ?EbookCover $cover = null;

    protected ?int $wordsCount = null;

    protected ?int $pagesCount = null;

    protected bool $countsParsed = false;

    protected ?float $execTime = null;

    /** @var array<string, mixed> */
    protected array $extras = [];

    protected function __construct(
        protected string $path,
        protected string $filename,
        protected string $extension,
        protected BaseArchive $archive,
        protected bool $hasMetadata = false,
    ) {
    }

    /**
     * Read an ebook file.
     */
    public static function read(string $path): ?self
    {
        $start = microtime(true);
        $filename = pathinfo($path, PATHINFO_BASENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension && ! in_array($extension, ['epub', 'pdf', 'cbz', 'cbr', 'cb7'])) {
            throw new \Exception("Unknown archive type: {$extension}");
        }

        $self = new self($path, $filename, $extension, Archive::read($path));

        if (in_array($extension, ['cbz', 'cbr', 'cb7', 'cbt'])) {
            $self->format = EbookFormatEnum::CBA;
        } elseif ($extension === 'pdf') {
            $self->format = EbookFormatEnum::PDF;
        } elseif ($extension === 'epub') {
            $self->format = EbookFormatEnum::EPUB;
        } else {
            // throw new \Exception("Unknown archive type: {$extension}");
        }

        $format = match ($self->format) {
            EbookFormatEnum::EPUB => $self->epub(),
            EbookFormatEnum::CBA => $self->cba(),
            EbookFormatEnum::PDF => $self->pdf(),
            default => null,
        };

        if ($format === null) {
            return null;
        }

        $self->metaTitle = MetaTitle::make($self);

        $time = microtime(true) - $start;
        $self->execTime = number_format((float) $time, 5, '.', '');

        return $self;
    }

    private function epub(): self
    {
        $this->metadata = EpubMetadata::make($this);
        $this->convertEbook();
        $this->cover = $this->metadata->toCover();

        return $this;
    }

    private function cba(): self
    {
        $this->metadata = CbaMetadata::make($this);
        $this->convertEbook();
        $this->cover = $this->metadata->toCover();

        return $this;
    }

    private function pdf(): self
    {
        $this->metadata = PdfMetadata::make($this);
        $this->convertEbook();
        $this->cover = $this->metadata->toCover();

        return $this;
    }

    private function convertEbook(): self
    {
        $ebook = $this->metadata->toEbook();

        $this->title = $ebook->title();
        $this->metaTitle = $ebook->metaTitle();
        $this->authorMain = $ebook->authorMain();
        $this->authors = $ebook->authors();
        $this->description = $ebook->description();
        $this->publisher = $ebook->publisher();
        $this->identifiers = $ebook->identifiers();
        $this->publishDate = $ebook->publishDate();
        $this->language = $ebook->language();
        $this->tags = $ebook->tags();
        $this->series = $ebook->series();
        $this->volume = $ebook->volume();
        $this->copyright = $ebook->copyright();

        return $this;
    }

    private function convertCounts(): self
    {
        $this->countsParsed = true;
        $counts = $this->metadata->toCounts();

        $this->wordsCount = $counts->wordsCount();
        $this->pagesCount = $counts->pagesCount();

        return $this;
    }

    public static function wordsByPage(): int
    {
        return 250;
    }

    public function toXml(string $path): ?string
    {
        $ebook = $this->archive->find($path);
        $content = $this->archive->content($ebook);

        return $content;
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
     * Can be null if the title is null.
     */
    public function metaTitle(): ?MetaTitle
    {
        return $this->metaTitle;
    }

    /**
     * First author of the book (useful if you need to display only one author).
     */
    public function authorMain(): ?BookAuthor
    {
        return $this->authorMain;
    }

    /**
     * All authors of the book.
     *
     * @return BookAuthor[]
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
    public function publishDate(): ?DateTime
    {
        return $this->publishDate;
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
     * Copyright of the book.
     */
    public function copyright(): ?string
    {
        return $this->copyright;
    }

    /**
     * Physical path to the ebook.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Filename of the ebook.
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * Extension of the ebook.
     */
    public function extension(): string
    {
        return $this->extension;
    }

    /**
     * Archive reader.
     */
    public function archive(): BaseArchive
    {
        return $this->archive;
    }

    /**
     * Whether the ebook has metadata.
     */
    public function hasMetadata(): bool
    {
        return $this->hasMetadata;
    }

    /**
     * Format of the ebook.
     */
    public function format(): ?EbookFormatEnum
    {
        return $this->format;
    }

    /**
     * Metadata of the ebook.
     */
    public function metadata(): ?EbookMetadata
    {
        return $this->metadata;
    }

    /**
     * Cover of the ebook.
     */
    public function cover(): ?EbookCover
    {
        return $this->cover;
    }

    /**
     * Word count of the ebook.
     */
    public function wordsCount(): ?int
    {
        if (! $this->countsParsed) {
            $this->convertCounts();
        }

        return $this->wordsCount;
    }

    /**
     * Page count of the ebook.
     */
    public function pagesCount(): ?int
    {
        if (! $this->countsParsed) {
            $this->convertCounts();
        }

        return $this->pagesCount;
    }

    /**
     * Execution time for parsing the ebook.
     */
    public function execTime(): ?float
    {
        return $this->execTime;
    }

    /**
     * Extras of the ebook.
     *
     * @return array<string, mixed>
     */
    public function extras(): array
    {
        return $this->extras;
    }

    /**
     * Whether the ebook has a cover.
     */
    public function hasCover(): bool
    {
        return $this->cover !== null;
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

    public function setAuthorMain(?BookAuthor $authorMain): self
    {
        $this->authorMain = $authorMain;

        return $this;
    }

    /**
     * @param  BookAuthor[]  $authors
     */
    public function setAuthors(array $authors): self
    {

        $this->authors = $authors;

        if (! $this->authorMain && count($this->authors) > 0) {
            $this->authorMain = reset($this->authors);
        }

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function setPublishDate(?DateTime $publishDate): self
    {
        $this->publishDate = $publishDate;

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

    public function setVolume(int|string|null $volume): self
    {
        if (is_string($volume)) {
            $volume = intval($volume);
        }

        $this->volume = $volume;

        return $this;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function setWordsCount(?int $wordsCount): self
    {
        $this->wordsCount = $wordsCount;

        return $this;
    }

    public function setPagesCount(?int $pagesCount): self
    {
        $this->pagesCount = $pagesCount;

        return $this;
    }

    public function setHasMetadata(bool $hasMetadata): self
    {
        $this->hasMetadata = $hasMetadata;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $extras
     */
    public function setExtras(array $extras): self
    {
        $this->extras = $extras;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authorMain' => $this->authorMain?->name(),
            'authors' => array_map(fn (BookAuthor $author) => $author->name(), $this->authors),
            'description' => $this->description,
            'publisher' => $this->publisher,
            'identifiers' => array_map(fn (BookIdentifier $identifier) => $identifier->toArray(), $this->identifiers),
            'date' => $this->publishDate?->format('Y-m-d H:i:s'),
            'language' => $this->language,
            'tags' => $this->tags,
            'series' => $this->series,
            'volume' => $this->volume,
            'wordsCount' => $this->wordsCount,
            'pagesCount' => $this->pagesCount,
            'path' => $this->path,
            'filename' => $this->filename,
            'extension' => $this->extension,
            'format' => $this->format,
            'metadata' => $this->metadata?->toArray(),
            'cover' => $this->cover?->toArray(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return "{$this->path} ({$this->format?->value})";
    }
}
