<?php

namespace Kiwilan\Ebook\Formats\Djvu;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;

class DjvuModule extends EbookModule
{
    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);

        return $self;
    }

    public function toEbook(): Ebook
    {
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

    public function toArray(): array
    {
        return [];
    }
}
