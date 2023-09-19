<?php

namespace Kiwilan\Ebook\Formats\Rtf;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\XmlReader\XmlReader;

class RtfModule extends EbookModule
{
    // protected ?Fb2Parser $parser = null;

    public static function make(Ebook $ebook): EbookModule
    {
        $self = new self($ebook);
        // $self->parser = Fb2Parser::make($ebook->getPath());
        $contents = file_get_contents($ebook->getPath());
        $xml = XmlReader::make($contents);
        ray($xml);
        ray($contents);

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
