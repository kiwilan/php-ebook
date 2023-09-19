<?php

namespace Kiwilan\Ebook\Formats\Mobi;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiReader;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;

/**
 * @docs https://stackoverflow.com/questions/11817047/php-library-to-parse-mobi
 * @docs https://wiki.mobileread.com/wiki/MOBI
 */
class MobiModule extends EbookModule
{
    protected ?MobiParser $parser = null;

    protected ?MobiReader $reader = null;

    protected ?string $cover = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = MobiParser::make($ebook->getPath());
        $self->reader = MobiReader::make($self->parser);

        return $self;
    }

    public function toEbook(): Ebook
    {
        if (! $this->reader) {
            return $this->ebook;
        }

        // $authors = [];
        // foreach ($reader->authors() as $author) {
        //     $authors[] = new BookAuthor($author);
        // }

        // $isbns = [];
        // foreach ($reader->isbns() as $isbn) {
        //     $isbns[] = new BookIdentifier($isbn);
        // }

        // $publishingDate = $reader->getPublishingDate();
        // if ($publishingDate) {
        //     $publishingDate = new DateTime($publishingDate);
        // }

        // $this->ebook->setAuthors($authors);
        // $this->ebook->setPublisher($reader->publisher());

        // $description = $reader->description();
        // $this->ebook->setDescription($this->descriptionToString($description));
        // $this->ebook->setDescriptionHtml($this->descriptionToHtml($description));

        // $this->ebook->setIdentifiers($isbns);
        // $this->ebook->setTags($reader->subjects());
        // $this->ebook->setPublishDate($publishingDate);
        // $this->ebook->setTitle($reader->updatedTitle());
        // $this->ebook->setLanguage($reader->language());

        // $this->ebook->setExtras([
        //     'contributor' => $reader->contributor(),
        // ]);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        return EbookCover::make(content: $this->cover);
    }

    public function toCounts(): Ebook
    {
        return $this->ebook;
    }

    public function toArray(): array
    {
        return [];
    }
}
