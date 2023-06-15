<?php

namespace Kiwilan\Ebook\Formats\Mobi;

class MobiPalmDOCHeader
{
    protected function __construct(
        protected int $compression = 0,
        protected int $textLength = 0,
        protected int $records = 0,
        protected int $recordSize = 0,
    ) {
    }

    /**
     * @param  resource  $stream
     * @param  MobiPalmRecord[]  $palmHeaders
     */
    public static function make(mixed $stream, array $palmHeaders): self
    {
        $self = new self();

        fseek($stream, $palmHeaders[0]->offset(), SEEK_SET);

        $content = fread($stream, 2);
        $self->compression = hexdec(bin2hex($content));

        $content = fread($stream, 2);
        $content = fread($stream, 4);
        $self->textLength = hexdec(bin2hex($content));

        $content = fread($stream, 2);
        $self->records = hexdec(bin2hex($content));

        $content = fread($stream, 2);
        $self->recordSize = hexdec(bin2hex($content));

        $content = fread($stream, 4);

        return $self;
    }

    public function compression(): int
    {
        return $this->compression;
    }

    public function textLength(): int
    {
        return $this->textLength;
    }

    public function records(): int
    {
        return $this->records;
    }

    public function recordSize(): int
    {
        return $this->recordSize;
    }
}
