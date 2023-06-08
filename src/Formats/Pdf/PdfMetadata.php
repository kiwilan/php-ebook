<?php

namespace Kiwilan\Ebook\Formats\Pdf;

use Kiwilan\Archive\Models\ArchiveMetadata;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCore;
use Kiwilan\Ebook\EbookCounts;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookMetadata;
use Kiwilan\Ebook\Tools\BookAuthor;

class PdfMetadata extends EbookMetadata
{
    protected ?ArchiveMetadata $metadata = null;

    protected function __construct(
    ) {
        parent::__construct(...func_get_args());
    }

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook, $ebook->archive()->metadata());

        return $self;
    }

    public function toEbook(): Ebook
    {
        ray($this);
        $core = EbookCore::make();
        // $core->setTitle($this->metadata->title());

        // $author = $this->metadata->author();
        // $authors = [];
        // if (str_contains($author, ',')) {
        //     $authors = explode(',', $author);
        // } elseif (str_contains($author, '&')) {
        //     $authors = explode(',', $author);
        // } elseif (str_contains($author, 'and')) {
        //     $authors = explode(',', $author);
        // } else {
        //     $authors[] = $author;
        // }

        // $creators = [];
        // foreach ($authors as $author) {
        //     $creators[] = new BookAuthor(
        //         name: trim($author),
        //     );
        // }

        // $core->setAuthors($creators);
        // $core->setDescription($this->metadata->subject());
        // $core->setPublisher($this->metadata->creator());
        // $core->setTags($this->metadata->keywords());
        // $core->setPublishDate($this->metadata->creationDate());

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        if (extension_loaded('imagick')) {
            return null;
        }

        $file = $this->ebook->archive()->first();
        $content = $this->ebook->archive()->content($file);
        $path = $file->path();

        return EbookCover::make($path, $content);
    }

    public function toCounts(): ?EbookCounts
    {
        $pages = $this->ebook->archive()->count();

        return EbookCounts::make(0, $pages);
    }

    public function toArray(): array
    {
        return [
            'metadata' => $this->metadata?->toArray(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
