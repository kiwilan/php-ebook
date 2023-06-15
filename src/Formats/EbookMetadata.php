<?php

namespace Kiwilan\Ebook\Formats;

use Kiwilan\Ebook\Formats\Audio\AudiobookMetadata;
use Kiwilan\Ebook\Formats\Cba\CbaMetadata;
use Kiwilan\Ebook\Formats\Epub\EpubMetadata;
use Kiwilan\Ebook\Formats\Mobi\MobiMetadata;
use Kiwilan\Ebook\Formats\Pdf\PdfMetadata;

class EbookMetadata
{
    protected function __construct(
        protected EbookModule $module,
        protected ?EpubMetadata $epub = null,
        protected ?MobiMetadata $mobi = null,
        protected ?CbaMetadata $cba = null,
        protected ?PdfMetadata $pdf = null,
        protected ?AudiobookMetadata $audiobook = null,
        protected ?string $type = null,
    ) {
    }

    public static function make(EbookModule $module): self
    {
        $self = new self($module);

        if ($module instanceof EpubMetadata) {
            $self->epub = $module;
            $self->type = 'epub';
        }

        if ($module instanceof MobiMetadata) {
            $self->mobi = $module;
            $self->type = 'mobi';
        }

        if ($module instanceof CbaMetadata) {
            $self->cba = $module;
            $self->type = 'cba';
        }

        if ($module instanceof PdfMetadata) {
            $self->pdf = $module;
            $self->type = 'pdf';
        }

        if ($module instanceof AudiobookMetadata) {
            $self->audiobook = $module;
            $self->type = 'audiobook';
        }

        return $self;
    }

    public function module(): EbookModule
    {
        return $this->module;
    }

    public function epub(): ?EpubMetadata
    {
        return $this->epub;
    }

    public function mobi(): ?MobiMetadata
    {
        return $this->mobi;
    }

    public function cba(): ?CbaMetadata
    {
        return $this->cba;
    }

    public function pdf(): ?PdfMetadata
    {
        return $this->pdf;
    }

    public function audiobook(): ?AudiobookMetadata
    {
        return $this->audiobook;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function hasEpub(): bool
    {
        return $this->epub !== null;
    }

    public function hasMobi(): bool
    {
        return $this->mobi !== null;
    }

    public function hasCba(): bool
    {
        return $this->cba !== null;
    }

    public function hasPdf(): bool
    {
        return $this->pdf !== null;
    }

    public function hasAudiobook(): bool
    {
        return $this->audiobook !== null;
    }

    public function toArray(): array
    {
        return [
            'epub' => $this->epub?->toArray(),
            'mobi' => $this->mobi?->toArray(),
            'cba' => $this->cba?->toArray(),
            'pdf' => $this->pdf?->toArray(),
            'audiobook' => $this->audiobook?->toArray(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
