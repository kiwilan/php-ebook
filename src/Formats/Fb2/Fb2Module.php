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
        $titleInfo = $this->parser->getTitleInfo();

        $this->ebook->setTitle($titleInfo['book-title'] ?? null);

        $authors = $titleInfo['author'] ?? null;
        if (is_array($authors)) {
            foreach ($authors as $author) {
                $firstName = $author['first-name'] ?? null;
                $lastName = $author['last-name'] ?? null;
                $author = new BookAuthor(
                    name: "$firstName $lastName",
                );
                $this->ebook->setAuthor($author);
            }
        }

        $keywords = $titleInfo['keywords'] ?? null;
        if (is_string($keywords)) {
            $keywords = explode(',', $keywords);
        }

        $genre = $titleInfo['genre'] ?? null;
        $this->ebook->setTags([
            $genre,
            ...$keywords,
        ]);

        $lang = $titleInfo['lang'] ?? null;
        $this->ebook->setLanguage($lang);

        $description = $titleInfo['annotation'] ?? null;
        $description = $this->arrayToHtml($description);

        $this->ebook->setDescription($this->descriptionToString($description));
        $this->ebook->setDescriptionHtml($this->descriptionToHtml($description));

        $documentInfo = $this->parser->getDocumentInfo();
        $uuid = $documentInfo['id'] ?? null;
        $uuid = new BookIdentifier($uuid, 'uuid');
        $this->ebook->setIdentifier($uuid);

        $publishInfo = $this->parser->getPublishInfo();
        $publisher = $publishInfo['publisher'] ?? null;

        $this->ebook->setPublisher($publisher);

        $year = $publishInfo['year'] ?? null;
        $year = new \DateTime($year);
        $this->ebook->setPublishDate($year);

        $isbn = $publishInfo['isbn'] ?? null;
        $isbn = new BookIdentifier($isbn);
        $this->ebook->setIdentifier($isbn);

        return $this->ebook;
    }

    public function getParser(): Fb2Parser
    {
        return $this->parser;
    }

    public function toCover(): ?EbookCover
    {
        $cover = $this->parser->getCover();

        return EbookCover::make(content: $cover);
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
