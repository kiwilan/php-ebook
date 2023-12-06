<?php

namespace Kiwilan\Ebook\Formats\Djvu;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\Djvu\Parser\DjvuParser;
use Kiwilan\Ebook\Formats\EbookModule;

class DjvuModule extends EbookModule
{
    protected ?DjvuParser $parser = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        $self->parser = DjvuParser::make($ebook);

        return $self;
    }

    public function getParser(): ?DjvuParser
    {
        return $this->parser;
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
