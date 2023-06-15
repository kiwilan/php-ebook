<?php

namespace Kiwilan\Ebook\Formats\Mobi;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookIdentifier;

/**
 * Inspired by https://stackoverflow.com/a/15199117/11008206
 */
class MobiMetadata extends EbookModule
{
    protected ?MobiPalmDOCHeader $docHeader = null;

    protected ?MobiHeader $mobiHeader = null;

    protected ?MobiExthHeader $exthHeader = null;

    protected ?MobiHeadMeta $headMeta = null;

    /** @var MobiPalmRecord[] */
    protected array $palmHeaders = [];

    /** @var string[] */
    protected array $errors = [];

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->create();

        return $self;
    }

    public function toEbook(): Ebook
    {
        $authors = [];
        foreach ($this->headMeta->authors() as $author) {
            $authors[] = new BookAuthor($author);
        }

        $isbns = [];
        foreach ($this->headMeta->isbns() as $isbn) {
            $isbns[] = new BookIdentifier($isbn);
        }

        $publishingDate = $this->headMeta->publishingDate();
        if ($publishingDate) {
            $publishingDate = new DateTime($publishingDate);
        }

        $this->ebook->setAuthors($authors);
        $this->ebook->setPublisher($this->headMeta->publisher());
        $this->ebook->setDescription($this->headMeta->description());
        $this->ebook->setIdentifiers($isbns);
        $this->ebook->setTags($this->headMeta->subjects());
        $this->ebook->setPublishDate($publishingDate);
        $this->ebook->setTitle($this->headMeta->updatedTitle());
        $this->ebook->setLanguage($this->headMeta->language());

        $this->ebook->setExtras([
            'contributor' => $this->headMeta->contributor(),
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

    private function create(): self
    {
        $handle = fopen($this->ebook->path(), 'r');
        if (! $handle) {
            $this->errors[] = 'Cannot open file';

            return $this;
        }

        fseek($handle, 60, SEEK_SET);
        $content = fread($handle, 8);
        if ($content !== 'BOOKMOBI') {
            $this->errors[] = 'Invalid file format';
            fclose($handle);

            return $this;
        }

        fseek($handle, 0, SEEK_SET);
        $name = fread($handle, 32);

        fseek($handle, 76, SEEK_SET);
        $content = fread($handle, 2);
        $records = hexdec(bin2hex($content));

        fseek($handle, 78, SEEK_SET);
        for ($i = 0; $i < $records; $i++) {
            $record = MobiPalmRecord::make($handle);
            $this->palmHeaders[] = $record;
        }

        $this->docHeader = MobiPalmDOCHeader::make($handle, $this->palmHeaders);

        $mobiStart = ftell($handle);
        $content = fread($handle, 4);
        if ($content !== 'MOBI') {
            $this->errors[] = 'No MOBI header';
            fclose($handle);

            return $this;
        }

        $this->mobiHeader = MobiHeader::make($handle, $mobiStart);

        fseek($handle, $mobiStart + $this->mobiHeader->length(), SEEK_SET);
        $content = fread($handle, 4);
        if ($content !== 'EXTH') {
            $this->errors[] = 'No EXTH header';
            fclose($handle);

            return $this;
        }

        $this->exthHeader = MobiExthHeader::make($handle);
        $this->headMeta = MobiHeadMeta::make($this->exthHeader->records());

        fclose($handle);

        return $this;
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
