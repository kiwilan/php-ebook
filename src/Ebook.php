<?php

namespace Kiwilan\Ebook;

use Kiwilan\Archive\Archive;
use Kiwilan\Archive\Readers\BaseArchive;
use Kiwilan\Ebook\Book\BookCreator;
use Kiwilan\Ebook\Cba\Cba;
use Kiwilan\Ebook\Cba\CbaCbam;
use Kiwilan\Ebook\Cba\CbaFormat;
use Kiwilan\Ebook\Epub\EpubContainer;
use Kiwilan\Ebook\Epub\EpubOpf;

class Ebook
{
    protected EpubOpf|CbaFormat|null $metadata = null;

    protected ?BookEntity $book = null;

    protected ?string $format = null; // epub, pdf, cba

    protected ?string $cover = null;

    protected function __construct(
        protected string $path,
        protected string $filename,
        protected string $extension,
        protected BaseArchive $archive,
        protected bool $hasMetadata = false,
    ) {
    }

    public static function read(string $path): self
    {
        $filename = pathinfo($path, PATHINFO_BASENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ($extension && ! in_array($extension, ['epub', 'pdf', 'cbz', 'cbr', 'cb7'])) {
            throw new \Exception("Unknown archive type: {$extension}");
        }

        $self = new self($path, $filename, $extension, Archive::read($path));
        if (in_array($extension, ['cbz', 'cbr', 'cb7', 'cbt'])) {
            $self->format = 'cba';
        } elseif ($extension === 'pdf') {
            $self->format = 'pdf';
        } else {
            $self->format = 'epub';
        }

        match ($self->format) {
            'epub' => $self->epub(),
            'cba' => $self->cba(),
            'pdf' => $self->pdf(),
        };

        return $self;
    }

    private function epub(): self
    {
        $xml = $this->archiveToXml('container.xml');
        if (! $xml) {
            return $this;
        }
        $container = EpubContainer::make($xml);

        $opf = $this->archiveToXml($container->opfPath());
        if (! $opf) {
            return $this;
        }
        $opf = EpubOpf::make($opf);
        $this->metadata = $opf;
        $this->book = $opf->toBook();

        $cover = $this->archive->find($this->metadata->coverPath());
        $coverContent = $this->archive->content($cover);
        $this->setCover($coverContent);

        $count = 0;
        foreach ($this->metadata->contentFiles() as $path) {
            $file = $this->archive->find($path);
            $content = $this->archive->content($file);
            $content = strip_tags($content);
            $content = preg_replace('/[\r\n|\n|\r)]+/', '', $content);
            $words = str_word_count($content, 1);

            $count += count($words);
        }
        $pageCount = (int) ceil($count / 250);
        $this->book->setPageCount($pageCount);
        $this->book->setWords($count);
        $this->hasMetadata = true;

        return $this;
    }

    private function cba(): self
    {
        $xml = $this->archiveToXml('xml');
        if (! $xml) {
            return $this;
        }
        $metadata = XmlReader::toArray($xml);

        $root = $metadata['@root'] ?? null;
        $metadataType = match ($root) {
            'ComicInfo' => 'cbam',
            'ComicBook' => 'cbml',
            default => null,
        };

        /** @var ?CbaFormat */
        $parser = match ($metadataType) {
            'cbam' => CbaCbam::class,
            // 'cbml' => CbaCbml::class,
            default => null,
        };

        if (! $parser) {
            throw new \Exception("Unknown metadata type: {$metadataType}");
        }

        $this->metadata = $parser::create($metadata);
        $this->book = $this->metadata->toBook();

        $files = $this->archive->filter('jpg');
        if (empty($files)) {
            $files = $this->archive->filter('jpeg');
        }

        if (! empty($files)) {
            $cover = $files[0];
            $coverContent = $this->archive->content($cover);
            $this->setCover($coverContent);
        }

        $this->hasMetadata = true;

        return $this;
    }

    private function pdf(): self
    {
        $this->book = BookEntity::make();
        $this->book->setTitle($this->archive->metadata()->title());

        $author = $this->archive->metadata()->author();
        $authors = [];
        if (str_contains($author, ',')) {
            $authors = explode(',', $author);
        } elseif (str_contains($author, '&')) {
            $authors = explode(',', $author);
        } elseif (str_contains($author, 'and')) {
            $authors = explode(',', $author);
        } else {
            $authors[] = $author;
        }

        $creators = [];
        foreach ($authors as $author) {
            $creators[] = new BookCreator(
                name: trim($author),
            );
        }

        $this->book->setAuthors($creators);
        $this->book->setDescription($this->archive->metadata()->subject());
        $this->book->setPublisher($this->archive->metadata()->creator());
        $this->book->setTags($this->archive->metadata()->keywords());
        $this->book->setDate($this->archive->metadata()->creationDate());
        $this->book->setPageCount($this->archive->count());

        if (extension_loaded('imagick')) {
            $coverContent = $this->archive->content($this->archive->first());
            $this->setCover($coverContent);
        }
        $this->hasMetadata = true;

        return $this;
    }

    private function archiveToXml(string $path): ?string
    {
        $file = $this->archive->find($path);
        if (! $file) {
            return null;
        }
        $content = $this->archive->content($file);

        return $content;
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
    // public function archive(): BaseArchive
    // {
    //     return $this->archive;
    // }

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
    public function format(): ?string
    {
        return $this->format;
    }

    /**
     * Metadata of the ebook.
     */
    public function metadata(): EpubOpf|CbaFormat|null
    {
        return $this->metadata;
    }

    /**
     * Cover of the ebook (saved as base64, auto convert to string).
     */
    public function cover(bool $convertBase64 = true): ?string
    {
        if (! $this->cover) {
            return null;
        }

        if ($convertBase64) {
            return base64_decode($this->cover);
        }

        return $this->cover;
    }

    /**
     * Book entity of the ebook.
     */
    public function book(): ?BookEntity
    {
        return $this->book;
    }

    public function setCover(?string $cover, bool $toBase64 = true): self
    {
        if ($toBase64) {
            $cover = base64_encode($cover);
        } else {
            $cover = $cover;
        }

        $this->cover = $cover;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'filename' => $this->filename,
            'extension' => $this->extension,
            'format' => $this->format,
            'metadata' => $this->metadata ? 'Exists (use `metadata()` to display it)' : null,
            'book' => $this->book ? 'Exists (use `book()` to display it)' : null,
            'cover' => $this->cover ? 'Exists (use `cover()` to display it)' : null,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return "{$this->path} ({$this->format})";
    }
}
