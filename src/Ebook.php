<?php

namespace Kiwilan\Ebook;

use DateTime;
use Kiwilan\Archive\Archive;
use Kiwilan\Archive\ArchiveZipCreate;
use Kiwilan\Archive\Readers\BaseArchive;
use Kiwilan\Ebook\Creator\EbookCreator;
use Kiwilan\Ebook\Enums\EbookFormatEnum;
use Kiwilan\Ebook\Formats\Audio\AudiobookModule;
use Kiwilan\Ebook\Formats\Cba\CbaModule;
use Kiwilan\Ebook\Formats\Djvu\DjvuModule;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\EbookParser;
use Kiwilan\Ebook\Formats\Epub\EpubModule;
use Kiwilan\Ebook\Formats\Fb2\Fb2Module;
use Kiwilan\Ebook\Formats\Mobi\MobiModule;
use Kiwilan\Ebook\Formats\Pdf\PdfModule;
use Kiwilan\Ebook\Models\BookAuthor;
use Kiwilan\Ebook\Models\BookDescription;
use Kiwilan\Ebook\Models\BookIdentifier;
use Kiwilan\Ebook\Models\MetaTitle;
use Kiwilan\Ebook\Utils\EbookUtils;

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

    protected int|float|null $volume = null;

    protected ?string $copyright = null;

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
        protected string $basename,
        protected string $extension,
        protected int $size = 0,
        protected ?DateTime $createdAt = null,
        protected ?BaseArchive $archive = null,
        protected ?\Kiwilan\Audio\Audio $audio = null,
        protected bool $isArchive = false,
        protected bool $isAudio = false,
        protected bool $isMobi = false,
        protected bool $isBadFile = false,
        protected ?EbookParser $parser = null,
        protected bool $hasParser = false,
    ) {}

    /**
     * Read an ebook file.
     */
    public static function read(string $path, ?string $format = null): ?self
    {
        $start = microtime(true);
        $self = self::parseFile($path, $format);

        $format = match ($self->format) {
            EbookFormatEnum::AUDIOBOOK => $self->audiobook(),
            EbookFormatEnum::CBA => $self->cba(),
            EbookFormatEnum::DJVU => $self->djvu(),
            EbookFormatEnum::EPUB => $self->epub(),
            EbookFormatEnum::FB2 => $self->fb2(),
            EbookFormatEnum::MOBI => $self->mobi(),
            EbookFormatEnum::PDF => $self->pdf(),
            default => null,
        };

        if ($format === null) {
            return null;
        }

        $self->parser = EbookParser::make($format);
        $self->convertEbook();
        $self->cover = $self->parser->getModule()->toCover();
        $self->metaTitle = MetaTitle::fromEbook($self);
        $self->clean();

        $time = microtime(true) - $start;
        $self->execTime = (float) number_format((float) $time, 5, '.', '');

        return $self;
    }

    /**
     * Check if an ebook file is valid.
     */
    public static function isValid(string $path): bool
    {
        $self = self::parseFile($path);

        return ! $self->isBadFile;
    }

    /**
     * Create an ebook file.
     */
    public static function create(string $path): ArchiveZipCreate
    {
        return EbookCreator::create($path);
    }

    /**
     * Parse an ebook file.
     */
    private static function parseFile(string $path, ?string $format = null): Ebook
    {
        $basename = pathinfo($path, PATHINFO_BASENAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = $format ?? pathinfo($path, PATHINFO_EXTENSION);

        $cbaExtensions = ['cbz', 'cbr', 'cb7', 'cbt'];
        $archiveExtensions = ['epub', 'pdf', ...$cbaExtensions];
        $audiobookExtensions = ['mp3', 'm4a', 'm4b', 'flac', 'ogg'];
        $mobipocketExtensions = ['mobi', 'azw', 'azw3', 'azw4', 'kf8', 'kfx', 'prc', 'tpz'];
        $extrasExtensions = ['lrf', 'lrx', 'fb2', 'rtf', 'ibooks', 'pdb', 'snb', 'djvu', 'djv'];
        $allowExtensions = [...$archiveExtensions, ...$audiobookExtensions, ...$mobipocketExtensions, ...$extrasExtensions];

        if (! file_exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        if ($extension && ! in_array($extension, $allowExtensions)) {
            throw new \Exception("Unknown file type extension: {$extension}");
        } elseif (! $extension) {
            throw new \Exception("File has no extension: {$path}");
        }

        $self = new self($path, $filename, $basename, $extension);

        $self->format = match ($extension) {
            'azw' => $self->format = EbookFormatEnum::MOBI,
            'azw3' => $self->format = EbookFormatEnum::MOBI,
            'djvu' => $self->format = EbookFormatEnum::DJVU,
            'djv' => $self->format = EbookFormatEnum::DJVU,
            'epub' => $self->format = EbookFormatEnum::EPUB,
            'mobi' => $self->format = EbookFormatEnum::MOBI,
            'lrf' => $self->format = EbookFormatEnum::MOBI,
            'kf8' => $self->format = EbookFormatEnum::MOBI,
            'kfx' => $self->format = EbookFormatEnum::MOBI,
            'prc' => $self->format = EbookFormatEnum::MOBI,
            // 'rtf' => $self->format = EbookFormatEnum::RTF,
            'fb2' => $self->format = EbookFormatEnum::FB2,
            'pdf' => $self->format = EbookFormatEnum::PDF,
            default => null,
        };

        if (! $self->format) {
            if (in_array($extension, $cbaExtensions)) {
                $self->format = EbookFormatEnum::CBA;
            } elseif (in_array($extension, $audiobookExtensions)) {
                $self->format = EbookFormatEnum::AUDIOBOOK;
            } else {
                // throw new \Exception("Unknown archive type: {$extension}");
            }
        }

        if (in_array($extension, $archiveExtensions)) {
            $self->isArchive = true;
        }

        if (in_array($extension, $audiobookExtensions)) {
            $self->isAudio = true;
        }

        if ($self->isArchive) {
            try {
                $archive = Archive::read($path);
                $self->archive = $archive;
            } catch (\Throwable $th) {
                error_log("Error reading archive: {$path}");
                $self->isBadFile = true;
            }
        }

        if ($self->isAudio) {
            AudiobookModule::checkPackage();
            $self->audio = \Kiwilan\Audio\Audio::read($path);
        }

        return $self;
    }

    private function audiobook(): EbookModule
    {
        return AudiobookModule::make($this);
    }

    private function cba(): EbookModule
    {
        return CbaModule::make($this);
    }

    private function djvu(): EbookModule
    {
        return DjvuModule::make($this);
    }

    private function epub(): EbookModule
    {
        return EpubModule::make($this);
    }

    private function fb2(): EbookModule
    {
        return Fb2Module::make($this);
    }

    private function mobi(): EbookModule
    {
        $this->isMobi = true;

        return MobiModule::make($this);
    }

    private function pdf(): EbookModule
    {
        return PdfModule::make($this);
    }

    private function clean(): self
    {
        $authors = [];
        foreach ($this->authors as $author) {
            if (! $author->getName()) {
                continue;
            }

            $authors[] = $author;
        }

        $this->authors = $authors;

        if ($this->authorMain && ! $this->authorMain->getName()) {
            $this->authorMain = null;

            if (count($this->authors) > 0) {
                $this->authorMain = reset($this->authors);
            }
        }

        return $this;
    }

    private function convertEbook(): self
    {
        $ebook = $this->parser->getModule()->toEbook();

        $this->title = $ebook->getTitle();
        $this->metaTitle = $ebook->getMetaTitle();
        $this->authorMain = $ebook->getAuthorMain();
        $this->authors = $ebook->getAuthors();
        $this->description = $ebook->getDescription();
        $this->publisher = $ebook->getPublisher();
        $this->identifiers = $ebook->getIdentifiers();
        $this->publishDate = $ebook->getPublishDate();
        $this->language = $ebook->getLanguage();
        $this->tags = $ebook->getTags();
        $this->series = $ebook->getSeries();
        $this->volume = $ebook->getVolume();
        $this->copyright = $ebook->getCopyright();
        $this->generateFileMetadata();

        return $this;
    }

    /**
     * Generate file metadata.
     */
    private function generateFileMetadata(): void
    {
        $file = new \SplFileInfo($this->getpath());
        if ($file->getMTime()) {
            $ts = gmdate("Y-m-d\TH:i:s\Z", $file->getMTime());
            $dt = new \DateTime($ts);
            $this->createdAt = $dt;
        }

        if ($file->getSize()) {
            $this->size = $file->getSize();
        }
    }

    private function convertCounts(): self
    {
        $this->countsParsed = true;
        $counts = $this->parser->getModule()->toCounts();

        $this->wordsCount = $counts->getWordsCount();
        $this->pagesCount = $counts->getPagesCount();

        return $this;
    }

    public static function wordsByPage(): int
    {
        return 250;
    }

    public function toXml(string $path): ?string
    {
        if ($this->isBadFile) {
            return null;
        }

        $ebook = $this->archive->find($path);
        $content = $this->archive->getContents($ebook);

        return $content;
    }

    /**
     * Title of the book.
     */
    public function getTitle(): ?string
    {

        return $this->title;
    }

    /**
     * Title metadata of the book with slug, sort title, series slug, etc.
     * Can be null if the title is null.
     */
    public function getMetaTitle(): ?MetaTitle
    {
        return $this->metaTitle;
    }

    /**
     * First author of the book (useful if you need to display only one author).
     */
    public function getAuthorMain(): ?BookAuthor
    {
        return $this->authorMain;
    }

    /**
     * All authors of the book.
     *
     * @return BookAuthor[]
     */
    public function getAuthors(): array
    {

        return $this->authors;
    }

    /**
     * Raw description of the book.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Advanced description with multi-options for the book.
     */
    public function getDescriptionAdvanced(): BookDescription
    {
        return BookDescription::make($this->description);
    }

    /**
     * Publisher of the book.
     */
    public function getPublisher(): ?string
    {

        return $this->publisher;
    }

    /**
     * Identifiers of the book.
     *
     * @return BookIdentifier[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * Publish date of the book.
     */
    public function getPublishDate(): ?DateTime
    {
        return $this->publishDate;
    }

    /**
     * Language of the book.
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Tags of the book.
     *
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Series of the book.
     */
    public function getSeries(): ?string
    {

        return $this->series;
    }

    /**
     * Volume of the book.
     */
    public function getVolume(): int|float|null
    {
        return $this->volume;
    }

    /**
     * Copyright of the book.
     */
    public function getCopyright(?int $limit = null): ?string
    {
        if ($limit) {
            return EbookUtils::limitLength($this->copyright, $limit);
        }

        return $this->copyright;
    }

    /**
     * Physical path to the ebook.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Filename of the ebook, e.g. `The Clan of the Cave Bear`.
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Basename of the ebook, e.g. `The Clan of the Cave Bear.epub`.
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * Extension of the ebook, e.g. `epub`.
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Size of the ebook, in bytes.
     *
     * You can use `getSizeHumanReadable()` to get the size in human readable format.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Size of the ebook in human readable format, e.g. `1.23 MB`.
     */
    public function getSizeHumanReadable(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        foreach ($units as $unit) {
            if ($bytes < 1024) {
                break;
            }

            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$unit;
    }

    /**
     * Creation date of the ebook.
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Archive reader, from `kiwilan/php-archive`.
     *
     * @docs https://github.com/kiwilan/php-archive
     */
    public function getArchive(): ?BaseArchive
    {
        return $this->archive;
    }

    /**
     * Audio reader, from `kiwilan/php-audio`.
     * You have to install `kiwilan/php-audio` to use this feature.
     *
     * @docs https://github.com/kiwilan/php-audio
     */
    public function getAudio(): ?\Kiwilan\Audio\Audio
    {
        return $this->audio;
    }

    /**
     * Whether the ebook is an audio.
     */
    public function isAudio(): bool
    {
        return $this->isAudio;
    }

    /**
     * Whether the ebook is a mobi.
     */
    public function isMobi(): bool
    {
        return ! $this->isAudio;
    }

    /**
     * Whether the ebook is a bad file.
     */
    public function isBadFile(): bool
    {
        return $this->isBadFile;
    }

    /**
     * Whether the ebook is an archive.
     */
    public function isArchive(): bool
    {
        return $this->isArchive;
    }

    /**
     * Whether the ebook has series.
     */
    public function hasSeries(): bool
    {
        return $this->series !== null;
    }

    /**
     * Whether the ebook has parser.
     *
     * @deprecated Use `hasParser()` instead.
     */
    public function hasMetadata(): bool
    {
        return $this->hasParser;
    }

    /**
     * Whether the ebook has parser.
     */
    public function hasParser(): bool
    {
        return $this->hasParser;
    }

    /**
     * Format of the ebook.
     */
    public function getFormat(): ?EbookFormatEnum
    {
        return $this->format;
    }

    /**
     * Parser of the ebook.
     *
     * @deprecated Use `getParser()` instead.
     */
    public function getMetadata(): ?EbookParser
    {
        return $this->parser;
    }

    /**
     * Parser of the ebook.
     */
    public function getParser(): ?EbookParser
    {
        return $this->parser;
    }

    /**
     * Cover of the ebook.
     */
    public function getCover(): ?EbookCover
    {
        return $this->cover;
    }

    /**
     * Word count of the ebook.
     */
    public function getWordsCount(): ?int
    {
        if ($this->wordsCount) {
            return $this->wordsCount;
        }

        if (! $this->countsParsed) {
            $this->convertCounts();
        }

        return $this->wordsCount;
    }

    /**
     * Page count of the ebook.
     */
    public function getPagesCount(): ?int
    {
        if ($this->pagesCount) {
            return $this->pagesCount;
        }

        if (! $this->countsParsed) {
            $this->convertCounts();
        }

        return $this->pagesCount;
    }

    /**
     * Execution time for parsing the ebook.
     */
    public function getExecTime(): ?float
    {
        return $this->execTime;
    }

    /**
     * Extras of the ebook.
     *
     * @return array<string, mixed>
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Get key from `extras` safely.
     */
    public function getExtra(string $key): mixed
    {
        if (! array_key_exists($key, $this->extras)) {
            return null;
        }

        return $this->extras[$key];
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
        if (! $ebook->getTitle()) {
            return $this;
        }

        $this->metaTitle = MetaTitle::fromEbook($ebook);

        return $this;
    }

    public function setAuthorMain(?BookAuthor $authorMain): self
    {
        $this->authorMain = $authorMain;

        return $this;
    }

    public function setAuthor(BookAuthor $author): self
    {
        $this->authors = [
            $author,
            ...$this->authors,
        ];

        if (! $this->authorMain) {
            $this->authorMain = $author;
        }

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

    public function setDescription(string|array|null $description): self
    {
        if (is_array($description)) {
            $description = implode("\n", $description);
        }

        $this->description = $description;

        return $this;
    }

    public function setPublisher(?string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function setIdentifier(BookIdentifier $identifier): self
    {
        $key = $identifier->getScheme() ?? uniqid();

        if (array_key_exists($key, $this->identifiers)) {
            $key = uniqid();
            $this->identifiers[$key] = $identifier;

            return $this;
        }

        $this->identifiers[$key] = $identifier;

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

    public function setTag(?string $tag): self
    {
        if (! $tag) {
            return $this;
        }

        $tag = trim($tag);
        $this->tags = [
            $tag,
            ...$this->tags,
        ];

        return $this;
    }

    /**
     * @param  string[]|null  $tags
     */
    public function setTags(?array $tags): self
    {
        if (! $tags) {
            return $this;
        }

        $this->tags = $tags;

        return $this;
    }

    public function setSeries(?string $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function setVolume(int|string|float|null $volume): self
    {
        $this->volume = EbookUtils::parseNumber($volume);

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

    public function setHasParser(bool $hasParser): self
    {
        $this->hasParser = $hasParser;

        return $this;
    }

    public function setExtra(mixed $value, ?string $key = null): self
    {
        if (! $key) {
            $this->extras[] = $value;

            return $this;
        }

        $this->extras[$key] = $value;

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
            'authorMain' => $this->authorMain?->getName(),
            'authors' => array_map(fn (BookAuthor $author) => $author->getName(), $this->authors),
            'description' => $this->description,
            'publisher' => $this->publisher,
            'identifiers' => array_map(fn (BookIdentifier $identifier) => $identifier->toArray(), $this->identifiers),
            'publishDate' => $this->publishDate?->format('Y-m-d H:i:s'),
            'language' => $this->language,
            'tags' => $this->tags,
            'series' => $this->series,
            'volume' => $this->volume,
            'wordsCount' => $this->wordsCount,
            'pagesCount' => $this->pagesCount,
            'path' => $this->path,
            'filename' => $this->filename,
            'basename' => $this->basename,
            'extension' => $this->extension,
            'format' => $this->format,
            'extras' => $this->extras,
            'metaTitle' => $this->metaTitle?->toArray(),
            'parser' => $this->parser?->toArray(),
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
