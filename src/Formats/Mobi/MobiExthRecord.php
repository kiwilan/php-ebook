<?php

namespace Kiwilan\Ebook\Formats\Mobi;

class MobiExthRecord
{
    protected function __construct(
        protected int $type = 0,
        protected int $length = 0,
        protected ?string $data = null,
    ) {
    }

    public static function make(mixed $stream): self
    {
        $self = new self();

        $content = fread($stream, 4);
        $self->type = hexdec(bin2hex($content));

        $content = fread($stream, 4);
        $self->length = hexdec(bin2hex($content));

        $self->data = fread($stream, $self->length - 8);

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
