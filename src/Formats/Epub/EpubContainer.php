<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\XmlReader\XmlReader;

/**
 * Transform `container.xml` file to an object.
 */
class EpubContainer
{
    protected ?string $opfPath = null;

    protected ?string $version = null;

    protected function __construct(
        protected XmlReader $xml,
    ) {
    }

    public static function make(string $content): self
    {
        $xml = XmlReader::make($content);

        $self = new self($xml);
        $self->opfPath = $self->parseOpfPath();
        $self->version = $self->xml->version();

        if (! $self->opfPath) {
            throw new \Exception("Can't parse opf path");
        }

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
        $rootfile = $this->xml->find('rootfile');

        if (! $rootfile) {
            return null;
        }

        $rootfile = reset($rootfile);
        $rootAttr = XmlReader::getAttributes($rootfile);

        if (! $rootAttr) {
            return null;
        }

        $fullPath = $rootAttr['full-path'] ?? null;

        return $fullPath;
    }

    public function toArray(): array
    {
        return [
            'opfPath' => $this->opfPath,
            'version' => $this->version,
        ];
    }
}
