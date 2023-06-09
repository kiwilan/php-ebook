<?php

namespace Kiwilan\Ebook\Formats;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\Cba\CbamMetadata;
use Kiwilan\Ebook\Formats\Epub\EpubMetadata;

abstract class EbookMetadata
{
    protected ?EpubMetadata $epub = null;

    protected ?CbamMetadata $cbam = null;

    protected function __construct(
        protected Ebook $ebook,
    ) {
    }

    abstract public static function make(Ebook $ebook): self;

    abstract public function toEbook(): Ebook;

    abstract public function toCover(): ?EbookCover;

    abstract public function toCounts(): Ebook;

    public function epub(): ?EpubMetadata
    {
        return $this->epub;
    }

    public function cbam(): ?CbamMetadata
    {
        return $this->cbam;
    }

    abstract public function toArray(): array;

    abstract public function toJson(): string;

    abstract public function __toString(): string;
}
