<?php

namespace Kiwilan\Ebook\Formats\Mobi;

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

    /**
     * @param  resource  $stream
     */
    public static function make(mixed $stream, int|false $mobiStart): self
    {
        $self = new self();

        $content = fread($stream, 4);
        $self->length = hexdec(bin2hex($content));

        $content = fread($stream, 4);
        $self->type = hexdec(bin2hex($content));

        $content = fread($stream, 4);
        $self->encoding = hexdec(bin2hex($content));

        $content = fread($stream, 4);
        $self->id = hexdec(bin2hex($content));

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
