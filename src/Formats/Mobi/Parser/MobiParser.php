<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

use Kiwilan\Ebook\Utils\Stream;

/**
 * Inspired by https://stackoverflow.com/a/15199117/11008206
 */
class MobiParser
{
    /**
     * @param  PalmRecord[]  $records
     */
    protected function __construct(
        protected Stream $stream,
        protected ?string $error = null,
        protected array $records = [],
        protected ?PalmDOCHeader $palmDOCHeader = null,
        protected ?MobiHeader $mobiHeader = null,
        protected ?ExthHeader $exthHeader = null,
    ) {
    }

    public static function make(string $path): ?self
    {
        $self = new self(
            stream: Stream::make($path),
        );
        $self->parse();

        return $self;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    private function parse(): self
    {
        $this->stream->seek(60);
        $content = $this->stream->read(8);

        if ($content !== 'BOOKMOBI') {
            $this->error = 'Invalid file format';
            $this->stream->close();

            return $this;
        }

        $this->stream->seek(0);
        $name = $this->stream->read(32);

        $this->stream->seek(76);
        $content = $this->stream->read(2);
        $records = $this->stream->binaryToDecimal($content);

        $this->stream->seek(78);
        for ($i = 0; $i < $records; $i++) {
            $record = new PalmRecord(
                offset: $this->stream->binaryToDecimal($this->stream->read(4)),
                attributes: $this->stream->binaryToDecimal($this->stream->read(1)),
                id: $this->stream->binaryToDecimal($this->stream->read(3)),
            );

            $this->records[] = $record;
        }

        $this->palmDOCHeader = new PalmDOCHeader();
        $this->stream->seek($this->records[0]->offset);
        $this->palmDOCHeader->compression = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->stream->read(2);
        $this->palmDOCHeader->textLength = $this->stream->binaryToDecimal($this->stream->read(4));
        $this->palmDOCHeader->records = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->palmDOCHeader->recordSize = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->stream->read(4);

        $mobiStart = $this->stream->tell();
        $header = $this->stream->read(4);
        if ($header !== 'MOBI') {
            $this->error = 'No MOBI header';

            return $this;
        }

        $this->mobiHeader = new MobiHeader(
            length: $this->stream->binaryToDecimal($this->stream->read(4)),
            type: $this->stream->binaryToDecimal($this->stream->read(4)),
            encoding: $this->stream->binaryToDecimal($this->stream->read(4)),
            id: $this->stream->binaryToDecimal($this->stream->read(4)),
            fileVersion: $this->stream->binaryToDecimal($this->stream->read(4)),
        );

        $this->stream->seek($mobiStart + $this->mobiHeader->length);
        $exthHeader = $this->stream->read(4);
        if ($exthHeader !== 'EXTH') {
            $this->error = 'No EXTH header';

            return $this;
        }

        $this->exthHeader = new ExthHeader(
            length: $this->stream->binaryToDecimal($this->stream->read(4)),
        );

        $records = $this->stream->binaryToDecimal($this->stream->read(4));
        for ($i = 0; $i < $records; $i++) {
            $record = new ExthRecord(
                type: $this->stream->binaryToDecimal($this->stream->read(4)),
                length: $this->stream->binaryToDecimal($this->stream->read(4)),
            );

            $record->data = $this->stream->read($record->length - 8);
            $this->exthHeader->records[$record->type] = $record;
        }

        ksort($this->exthHeader->records);

        $this->stream->close();

        return $this;
    }

    public function getStream(): Stream
    {
        return $this->stream;
    }

    public function getPalmDOCHeader(): ?PalmDOCHeader
    {
        return $this->palmDOCHeader;
    }

    public function getMobiHeader(): ?MobiHeader
    {
        return $this->mobiHeader;
    }

    public function getExthHeader(): ?ExthHeader
    {
        return $this->exthHeader;
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    protected function getRecord(int $type): ?ExthRecord
    {
        foreach ($this->exthHeader->records as $record) {
            if ($record->type == $type) {
                return $record;
            }
        }

        return null;
    }

    public function getRecordData(int $type): ?string
    {
        $record = $this->getRecord($type);
        if ($record) {
            return $record->data;
        }

        return null;
    }
}

class PalmDOCHeader
{
    public function __construct(
        public int $compression = 0,
        public int $textLength = 0,
        public int $records = 0,
        public int $recordSize = 0,
    ) {
    }
}

class PalmRecord
{
    public function __construct(
        public int $offset = 0,
        public int $attributes = 0,
        public int $id = 0
    ) {
    }
}

class MobiHeader
{
    public function __construct(
        public int $length = 0,
        public int $type = 0,
        public int $encoding = 0,
        public int $id = 0,
        public int $fileVersion = 0,
    ) {
    }
}

class ExthHeader
{
    /**
     * @param  ExthRecord[]  $records
     */
    public function __construct(
        public int $length = 0,
        public array $records = [],
    ) {
    }
}

class ExthRecord
{
    public function __construct(
        public int $type = 0,
        public int $length = 0,
        public ?string $data = null,
    ) {
    }
}
