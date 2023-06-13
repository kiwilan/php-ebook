<?php

namespace Kiwilan\Ebook\Formats\Pdf;

use Kiwilan\Archive\Models\PdfMeta;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;

class PdfMetadata extends EbookModule
{
    protected ?PdfMeta $meta = null;

    protected function __construct(
    ) {
        parent::__construct(...func_get_args());
    }

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);
        $self->meta = $ebook->archive()->pdf();

        return $self;
    }

    public function toEbook(): Ebook
    {
        $this->ebook->setTitle($this->meta->title());

        $author = $this->meta->author();
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
            $creators[] = new BookAuthor(
                name: trim($author),
            );
        }

        $this->ebook->setAuthors($creators);
        $this->ebook->setDescription($this->meta->subject());
        $this->ebook->setPublisher($this->meta->creator());
        $this->ebook->setTags($this->meta->keywords());
        $this->ebook->setPublishDate($this->meta->creationDate());

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        if (! extension_loaded('imagick')) {
            return null;
        }

        $file = $this->ebook->archive()->first();
        $content = $this->ebook->archive()->content($file);
        $path = $file->path();

        return EbookCover::make($path, $content);
    }

    public function toCounts(): Ebook
    {
        $this->ebook->setPagesCount($this->ebook->archive()->count());

        return $this->ebook;
    }

    public function toArray(): array
    {
        return [
            'metadata' => $this->meta?->toArray(),
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
