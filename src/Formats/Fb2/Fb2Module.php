<?php

namespace Kiwilan\Ebook\Formats\Fb2;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\Fb2\Parser\Fb2Parser;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;

class Fb2Module extends EbookModule
{
    protected ?Fb2Parser $parser = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = Fb2Parser::make($ebook->getPath());

        return $self;
    }

    public function toEbook(): Ebook
    {
        $descriptionInfo = $this->parser->getDescription() ?? null;
        if (! $descriptionInfo) {
            return $this->ebook;
        }

        $this->ebook->setTitle($descriptionInfo->title?->bookTitle);

        $authors = $descriptionInfo->title?->author;
        if (is_array($authors)) {
            foreach ($authors as $author) {
                $firstName = $author->firstName ?? null;
                $lastName = $author->lastName ?? null;
                $author = new BookAuthor(
                    name: "$firstName $lastName",
                );
                $this->ebook->setAuthor($author);
            }
        }

        $keywords = $descriptionInfo->title?->keywords;
        if (is_string($keywords)) {
            $keywords = explode(',', $keywords);
        }

        $genre = $descriptionInfo->title?->genre;
        $this->ebook->setTags([
            $genre,
            ...$keywords,
        ]);

        $lang = $descriptionInfo->title?->lang;
        $this->ebook->setLanguage($lang);

        $description = $descriptionInfo->title?->annotation;
        $description = $this->arrayToHtml($description);

        $this->ebook->setDescription($this->descriptionToString($description));
        $this->ebook->setDescriptionHtml($this->descriptionToHtml($description));

        $documentInfo = $descriptionInfo->document;
        $uuid = $documentInfo?->id ?? null;
        if ($uuid) {
            $uuid = new BookIdentifier($uuid, 'uuid');
            $this->ebook->setIdentifier($uuid);
        }

        $publishInfo = $descriptionInfo->publish;
        if ($publishInfo) {
            $this->ebook->setPublisher($publishInfo?->publisher ?? null);

            $year = $publishInfo->year ?? null;
            if ($year) {
                $year = new \DateTime($year);
                $this->ebook->setPublishDate($year);
            }

            if ($publishInfo->isbn) {
                $isbn = new BookIdentifier($publishInfo->isbn);
                $this->ebook->setIdentifier($isbn);
            }
        }

        if ($descriptionInfo->title?->sequence) {
            $series = $descriptionInfo->title->sequence->name ?? null;
            $number = $descriptionInfo->title->sequence->number ?? null;

            $this->ebook->setSeries($series);
            $this->ebook->setVolume($number);
        }

        return $this->ebook;
    }

    public function getParser(): Fb2Parser
    {
        return $this->parser;
    }

    public function toCover(): ?EbookCover
    {
        $cover = $this->parser->getCover();

        return EbookCover::make(contents: $cover);
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
