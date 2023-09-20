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

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = MobiParser::make($ebook->getPath());

        return $self;
    }

    public function toEbook(): Ebook
    {
        if (! $this->parser->isValid()) {
            return $this->ebook;
        }

        foreach ($this->parser->get(MobiReader::AUTHOR_100, true) as $author) {
            $this->ebook->setAuthor(new BookAuthor($author));
        }

        foreach ($this->parser->get(MobiReader::ISBN_104, true) as $isbn) {
            $this->ebook->setIdentifier(new BookIdentifier($isbn));
        }

        $this->ebook->setIdentifier(new BookIdentifier($this->parser->get(MobiReader::SOURCE_112), 'source'));
        $this->ebook->setIdentifier(new BookIdentifier($this->parser->get(MobiReader::ASIN_113), 'asin'));

        $publishingDate = $this->parser->get(MobiReader::PUBLISHINGDATE_106);
        if ($publishingDate) {
            $publishingDate = new DateTime($publishingDate);
        }

        $this->ebook->setPublisher($this->parser->get(MobiReader::PUBLISHER_101));

        $description = $this->parser->get(MobiReader::DESCRIPTION_103);
        $this->ebook->setDescription($this->descriptionToString($description));
        $this->ebook->setDescriptionHtml($this->descriptionToHtml($description));

        foreach ($this->parser->get(MobiReader::SUBJECT_105, true) as $value) {
            $this->ebook->setTag($value);
        }

        $this->ebook->setPublishDate($publishingDate);
        $this->ebook->setTitle($this->parser->get(MobiReader::UPDATEDTITLE_503));
        $this->ebook->setLanguage($this->parser->get(MobiReader::LANGUAGE_524));
        $this->ebook->setCopyright($this->parser->get(MobiReader::CONTRIBUTOR_108));

        foreach ($this->parser->getExthRecords() as $value) {
            $this->ebook->setExtra($value->data);
        }

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $items = $this->parser->getImages()->getItems();
        if (count($items) === 0) {
            return null;
        }

        return EbookCover::make(contents: end($items));
    }

    public function toCounts(): Ebook
    {
        return $this->ebook;
    }

    public function toArray(): array
    {
        return [];
    }

    public function getParser(): ?MobiParser
    {
        return $this->parser;
    }
}
