<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

class MobiHeader
{
    protected function __construct(
        protected int $length = 0,
        protected int $type = 0,
        protected int $encoding = 0,
        protected int $id = 0,
        protected int $fileVersion = 0,
    ) {
    }

    public static function make(StreamParser $stream): self
    {
        $self = new self();

        $self->length = $stream->toInt(4);
        $self->type = $stream->toInt(4);
        $self->encoding = $stream->toInt(4);
        $self->id = $stream->toInt(4);

        return $self;
    }

    public function length(): int
    {
        return $this->length;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function encoding(): int
    {
        return $this->encoding;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function fileVersion(): int
    {
        return $this->fileVersion;
    }
}
