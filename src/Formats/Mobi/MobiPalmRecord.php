<?php

namespace Kiwilan\Ebook\Formats\Mobi;

class MobiPalmRecord
{
    protected function __construct(
        protected int $offset = 0,
        protected int $attributes = 0,
        protected int $id = 0,
    ) {
    }

    /**
     * @param  resource  $stream
     */
    public static function make(mixed $stream): self
    {
        $self = new self();

        $content = fread($stream, 4);
        $self->offset = hexdec(bin2hex($content));

        $content = fread($stream, 1);
        $self->attributes = hexdec(bin2hex($content));

        $content = fread($stream, 3);
        $self->id = hexdec(bin2hex($content));

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
