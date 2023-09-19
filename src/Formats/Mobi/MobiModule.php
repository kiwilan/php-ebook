<?php

namespace Kiwilan\Ebook\Formats\Mobi;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;

/**
 * @docs https://stackoverflow.com/questions/11817047/php-library-to-parse-mobi
 * @docs https://wiki.mobileread.com/wiki/MOBI
 */
class MobiModule extends EbookModule
{
    protected ?MobiParser $parser = null;

    protected ?string $cover = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = MobiParser::make($ebook->getPath());

        return $self;
    }

    public function toEbook(): Ebook
    {
        if (! $this->parser->getReader() || empty($this->parser->getReader()->getRecords())) {
            return $this->ebook;
        }

        $reader = $this->parser->getReader();

        foreach ($reader->get(100, true) as $author) {
            $this->ebook->setAuthor(new BookAuthor($author));
        }

        foreach ($reader->get(104, true) as $isbn) {
            $this->ebook->setIdentifier(new BookIdentifier($isbn));
        }

        $this->ebook->setIdentifier(new BookIdentifier($reader->get(113), '113'));
        $this->ebook->setIdentifier(new BookIdentifier($reader->get(112), '112'));

        $publishingDate = $reader->get(106);
        if ($publishingDate) {
            $publishingDate = new DateTime($publishingDate);
        }

        $this->ebook->setPublisher($reader->get(101));

        $description = $reader->get(103);
        $this->ebook->setDescription($this->descriptionToString($description));
        $this->ebook->setDescriptionHtml($this->descriptionToHtml($description));

        foreach ($reader->get(105, true) as $value) {
            $this->ebook->setTag($value);
        }

        $this->ebook->setPublishDate($publishingDate);
        $this->ebook->setTitle($reader->get(503));
        $this->ebook->setLanguage($reader->get(524));
        $this->ebook->setCopyright($reader->get(108));

        foreach ($reader->getRecords() as $value) {
            $this->ebook->setExtra($value->data);
        }

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
