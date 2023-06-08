<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\Ebook\XmlReader;

/**
 * Transform `container.xml` file to an object.
 */
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
        $xml = XmlReader::toArray($content);

        $self = new self($xml);
        $self->opfPath = $self->parseOpfPath();
        $self->version = $self->parseVersion();

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
        if (! isset($this->xml['rootfiles']['rootfile'])) {
            return null;
        }

        $root = $this->xml['rootfiles']['rootfile'];
        if (! array_key_exists('@attributes', $root)) {
            return null;
        }

        $rootAttr = $root['@attributes'];
        $fullPath = $rootAttr['full-path'] ?? null;

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

    public function toArray(): array
    {
        return [
            'opfPath' => $this->opfPath,
            'version' => $this->version,
        ];
    }
}
