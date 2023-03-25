<?php

namespace Kiwilan\Ebook\Cba;

use Kiwilan\Ebook\EbookXmlReader;

class CbaXml
{
    protected array $metadata = [];

    protected ?string $title = null;

    protected ?string $series = null;

    protected ?int $number = null;

    protected ?string $writer = null;

    protected ?string $publisher = null;

    protected ?string $languageIso = null;

    protected function __construct(
    ) {
    }

    public static function make(string $content): self
    {
        $xml = EbookXmlReader::make($content);
        $self = new self();

        $self->metadata = $xml;

        $self->parseMetadata();

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function series(): ?string
    {
        return $this->series;
    }

    public function number(): ?int
    {
        return $this->number;
    }

    public function writer(): ?string
    {
        return $this->writer;
    }

    public function publisher(): ?string
    {
        return $this->publisher;
    }

    public function languageIso(): ?string
    {
        return $this->languageIso;
    }

    private function parseMetadata(): self
    {
        $this->title = $this->metadata['Title'] ?? null;
        $this->series = $this->metadata['Series'] ?? null;
        $this->number = $this->metadata['Number'] ?? null;
        $this->writer = $this->metadata['Writer'] ?? null;
        $this->publisher = $this->metadata['Publisher'] ?? null;
        $this->languageIso = $this->metadata['LanguageISO'] ?? null;

        return $this;
    }
}
