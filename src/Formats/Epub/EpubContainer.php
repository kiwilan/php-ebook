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
        protected array $xml,
    ) {
    }

    public static function make(string $content): self
    {
        $xml = XmlReader::make($content)->content();

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
        $container = $this->xml['container'] ?? null;

        if (! $container) {
            return null;
        }

        if (! isset($container['rootfiles']['rootfile'])) {
            return null;
        }

        $root = $container['rootfiles']['rootfile'];
        if (! array_key_exists('@attributes', $root)) {
            return null;
        }

        $rootAttr = $root['@attributes'];
        $fullPath = $rootAttr['full-path'] ?? null;

        return $fullPath;
    }

    private function parseVersion(): ?string
    {
        $container = $this->xml['container'] ?? null;

        if (! isset($container['@attributes'])) {
            return null;
        }

        $attr = $container['@attributes'];

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
