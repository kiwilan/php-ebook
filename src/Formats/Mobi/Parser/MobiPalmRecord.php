<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

class MobiPalmRecord
{
    protected function __construct(
        protected int $offset = 0,
        protected int $attributes = 0,
        protected int $id = 0,
    ) {
    }

    public static function make(StreamParser $stream): self
    {
        $self = new self();

        $self->offset = $stream->toInt(4);
        $self->attributes = $stream->toInt(1);
        $self->id = $stream->toInt(3);

        return $self;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function attributes(): int
    {
        return $this->attributes;
    }

    public function id(): int
    {
        return $this->id;
    }
}
