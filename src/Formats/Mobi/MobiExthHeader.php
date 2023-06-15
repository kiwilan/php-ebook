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

    protected function getRecord(int $type): ?MobiExthRecord
    {
        foreach ($this->records as $record) {
            if ($record->type() == $type) {
                return $record;
            }
        }

        return null;
    }

    protected function getRecordData(int $type): ?string
    {
        $record = $this->getRecord($type);
        if ($record) {
            return $record->data();
        }

        return null;
    }

    public function title()
    {
        return $this->getRecordData(503);
    }

    public function author()
    {
        return $this->getRecordData(100);
    }

    public function isbn()
    {
        return $this->getRecordData(104);
    }

    public function subject()
    {
        return $this->getRecordData(105);
    }

    public function publisher()
    {
        return $this->getRecordData(101);
    }

    public function publishDate()
    {
        return $this->getRecordData(102);
    }

    public function review()
    {
        return $this->getRecordData(106);
    }

    public function contributor()
    {
        return $this->getRecordData(107);
    }

    public function rights()
    {
        return $this->getRecordData(108);
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
