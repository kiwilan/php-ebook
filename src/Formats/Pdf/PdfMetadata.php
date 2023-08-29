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

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);
        $self->meta = $ebook->getArchive()->getPdf();

        return $self;
    }

    public function toEbook(): Ebook
    {
        $this->ebook->setTitle($this->meta->getTitle());

        $author = $this->meta->getAuthor();
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
        $this->ebook->setDescription($this->meta->getSubject());
        $this->ebook->setPublisher($this->meta->getCreator());
        $this->ebook->setTags($this->meta->getKeywords());
        $this->ebook->setPublishDate($this->meta->getCreationDate());

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        if (! extension_loaded('imagick') || $this->ebook->getArchive() === null) {
            return null;
        }

        $file = $this->ebook->getArchive()->getFirst();
        $content = $this->ebook->getArchive()->getContent($file);
        $path = $file->getPath();

        return EbookCover::make($path, $content);
    }

    public function getMeta(): ?PdfMeta
    {
        return $this->meta;
    }

    public function toCounts(): Ebook
    {
        $this->ebook->setPagesCount($this->ebook->getArchive()?->getCount());

        return $this->ebook;
    }

    public function toArray(): array
    {
        return [
            'metadata' => $this->meta?->toArray(),
        ];
    }
}
