<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

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
     * @param  MobiPalmRecord[]  $palmHeaders
     */
    public static function make(StreamParser $stream, array $palmHeaders): self
    {
        $self = new self();

        $stream->seek($palmHeaders[0]->offset());
        $self->compression = $stream->toInt(2);
        $self->textLength = $stream->toInt([2, 4]);
        $self->records = $stream->toInt(2);
        $self->recordSize = $stream->toInt(2);

        $stream->read(4);

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
