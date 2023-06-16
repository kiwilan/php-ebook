<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

class MobiExthRecord
{
    protected function __construct(
        protected int $type = 0,
        protected int $length = 0,
        protected ?string $data = null,
    ) {
    }

    public static function make(StreamParser $stream): self
    {
        $self = new self();

        $self->type = $stream->toInt(4);
        $self->length = $stream->toInt(4);
        $self->data = $stream->read($self->length - 8);

        return $self;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function length(): int
    {
        return $this->length;
    }

    public function data(): ?string
    {
        return $this->data;
    }
}
