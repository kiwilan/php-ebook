<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

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

    public static function make(StreamParser $stream): self
    {
        $self = new self();

        $self->length = $stream->toInt(4);
        $records = $stream->toInt(4);

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
