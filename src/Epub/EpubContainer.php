<?php

namespace Kiwilan\Ebook\Epub;

use Kiwilan\Ebook\EbookXmlReader;

class EpubContainer
{
    protected ?string $opfPath = null;

    protected ?string $version = null;

    protected function __construct(
        protected array $xml,
    ) {
    }

    public static function make(string $content): self
    {
        $xml = EbookXmlReader::make($content);

        $self = new self($xml);
        $self->opfPath = $self->parseOpfPath();
        $self->version = $self->parseVersion();

        return $self;
    }

    public function opfPath(): ?string
    {
        return $this->opfPath;
    }

    public function version(): ?string
    {
        return $this->version;
    }

    private function parseOpfPath(): ?string
    {
        if (! isset($this->xml['rootfiles']['rootfile'])) {
            return null;
        }

        $root = $this->xml['rootfiles']['rootfile'];
        if (! array_key_exists('@attributes', $root)) {
            return null;
        }

        $rootAttr = $root['@attributes'];

        $fullPath = $rootAttr['full-path'] ?? null;
        $mediaType = $rootAttr['media-type'] ?? null;

        return $fullPath;
    }

    private function parseVersion(): ?string
    {
        if (! isset($this->xml['@attributes'])) {
            return null;
        }

        $attr = $this->xml['@attributes'];

        return $attr['version'] ?? null;
    }
}
