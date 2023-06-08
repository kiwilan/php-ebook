<?php

namespace Kiwilan\Ebook\Formats\Cba;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCounts;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookMetadata;
use Kiwilan\Ebook\XmlReader;

class CbaMetadata extends EbookMetadata
{
    protected ?CbaBase $metadata = null;

    protected function __construct(
    ) {
        parent::__construct(...func_get_args());
    }

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);

        $xml = $ebook->toXml('xml');
        if (! $xml) {
            return $self;
        }
        $metadata = XmlReader::toArray($xml);

        $root = $metadata['@root'] ?? null;
        $metadataType = match ($root) {
            'ComicInfo' => 'cbam',
            'ComicBook' => 'cbml',
            default => null,
        };

        /** @var ?CbaBase */
        $parser = match ($metadataType) {
            'cbam' => CbaCbam::class,
            // 'cbml' => CbaCbml::class,
            default => null,
        };

        if (! $parser) {
            throw new \Exception("Unknown metadata type: {$metadataType}");
        }

        $self->metadata = $parser::make($metadata);

        return $self;
    }

    public function toEbook(): Ebook
    {
        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $files = $this->ebook->archive()->filter('jpg');
        if (empty($files)) {
            $files = $this->ebook->archive()->filter('jpeg');
        }

        if (! empty($files)) {
            $ebook = $files[0];
            $content = $this->ebook->archive()->content($ebook);

            return EbookCover::make($ebook->path(), $content);
        }

        return null;
    }

    public function toCounts(): ?EbookCounts
    {
        $pages = $this->metadata?->pageCount();

        return EbookCounts::make(null, $pages);
    }

    public function toArray(): array
    {
        return $this->metadata->toArray();
    }

    public function toJson(): string
    {
        return $this->metadata->toJson();
    }

    public function __toString(): string
    {
        return $this->metadata->__toString();
    }
}
