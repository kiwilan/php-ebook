<?php

namespace Kiwilan\Ebook\Formats\Mobi;

class MobiExthHeader
{
    /**
     * @param  MobiExthRecord[]  $records
     */
    protected function __construct(
        public int $length = 0,
        public array $records = [],
    ) {
    }

    public static function make(mixed $stream): self
    {
        $self = new self();

        $content = fread($stream, 4);
        $self->length = hexdec(bin2hex($content));

        $content = fread($stream, 4);
        $records = hexdec(bin2hex($content));

        for ($i = 0; $i < $records; $i++) {
            $record = MobiExthRecord::make($stream);
            $self->records[] = $record;
        }

        return $self;
    }

    public function length(): int
    {
        return $this->length;
    }

    /**
     * @return MobiExthRecord[]
     */
    public function records(): array
    {
        return $this->records;
    }
}
