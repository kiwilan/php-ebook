<?php

namespace Kiwilan\Ebook\Formats\Mobi;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;

class MobiMetadata extends EbookModule
{
    protected ?MobiParser $parser = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = MobiParser::make($ebook->path());

        return $self;
    }

    public function toEbook(): Ebook
    {
        $reader = $this->parser->reader();

        if (! $reader) {
            return $this->ebook;
        }

        $authors = [];
        foreach ($reader->authors() as $author) {
            $authors[] = new BookAuthor($author);
        }

        $isbns = [];
        foreach ($reader->isbns() as $isbn) {
            $isbns[] = new BookIdentifier($isbn);
        }

        $publishingDate = $reader->publishingDate();
        if ($publishingDate) {
            $publishingDate = new DateTime($publishingDate);
        }

        $this->ebook->setAuthors($authors);
        $this->ebook->setPublisher($reader->publisher());
        $this->ebook->setDescription($reader->description());
        $this->ebook->setIdentifiers($isbns);
        $this->ebook->setTags($reader->subjects());
        $this->ebook->setPublishDate($publishingDate);
        $this->ebook->setTitle($reader->updatedTitle());
        $this->ebook->setLanguage($reader->language());

        $this->ebook->setExtras([
            'contributor' => $reader->contributor(),
        ]);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        return null;
    }

    public function toCounts(): Ebook
    {
        return $this->ebook;
    }

    public function toArray(): array
    {
        return [];
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
