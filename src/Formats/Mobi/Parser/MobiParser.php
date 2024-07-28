<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

use Kiwilan\Ebook\Utils\Stream;

/**
 * Inspired by https://stackoverflow.com/a/15199117/11008206
 */
class MobiParser
{
    /**
     * @param  string[]  $errors
     * @param  PalmRecord[]  $palmRecords
     * @param  ExthRecord[]  $exthRecords
     */
    protected function __construct(
        protected Stream $stream,
        protected ?array $errors = [],
        protected array $palmRecords = [],
        protected array $exthRecords = [],
        protected ?PalmDOCHeader $palmDOCHeader = null,
        protected ?MobiHeader $mobiHeader = null,
        protected ?ExthHeader $exthHeader = null,
        protected ?MobiImages $images = null,
        protected bool $isValid = false,
    ) {}

    public static function make(string $path): ?self
    {
        $self = new self(Stream::make($path));
        $self->parse();
        $self->images = MobiImages::make($path);

        if (empty($self->errors)) {
            $self->errors = null;
        }

        return $self;
    }

    public function get(int $record, bool $asArray = false): array|string|null
    {
        $data = $this->getRecordData($record);

        if ($asArray) {
            return $data;
        }

        if (count($data) === 1) {
            return $data[0];
        }

        return implode(', ', $data);
    }

    private function parse(): self
    {
        $this->stream->seek(60);
        $content = $this->stream->read(8);

        if ($content !== 'BOOKMOBI') {
            $this->errors[] = "File format invalid: {$content} (expected BOOKMOBI)";
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

            $this->palmRecords[] = $record;
        }

        $this->palmDOCHeader = new PalmDOCHeader;
        $this->stream->seek($this->palmRecords[0]->offset);
        $this->palmDOCHeader->compression = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->stream->read(2);
        $this->palmDOCHeader->textLength = $this->stream->binaryToDecimal($this->stream->read(4));
        $this->palmDOCHeader->records = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->palmDOCHeader->recordSize = $this->stream->binaryToDecimal($this->stream->read(2));
        $this->stream->read(4);

        $mobiStart = $this->stream->tell();
        $header = $this->stream->read(4);
        if ($header !== 'MOBI') {
            $this->errors[] = "Header invalid: {$header} (expected MOBI)";
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
            $this->errors[] = "EXTH header invalid: {$exthHeader} (expected EXTH)";
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
            $this->exthHeader->records[] = $record;
        }

        $this->exthRecords = $this->exthHeader->records;
        if (empty($this->errors)) {
            $this->isValid = true;
        }

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

    /**
     * @return PalmRecord[]
     */
    public function getPalmRecords(): array
    {
        return $this->palmRecords;
    }

    /**
     * @return ExthRecord[]
     */
    public function getExthRecords(): array
    {
        return $this->exthRecords;
    }

    /**
     * @return string[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getImages(): ?MobiImages
    {
        return $this->images;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return ExthRecord[]|null
     */
    private function getRecord(int $type): ?array
    {
        if (! $this->exthHeader?->records) {
            return null;
        }

        $records = [];
        foreach ($this->exthHeader->records as $record) {
            if ($record->type === $type) {
                $records[] = $record;
            }
        }

        if (count($records) === 0) {
            return null;
        }

        return $records;
    }

    /**
     * @return string[]|null
     */
    private function getRecordData(int $type): ?array
    {
        $records = $this->getRecord($type);
        $data = [];

        if ($records) {
            foreach ($records as $value) {
                $data[] = $value->data;
            }

            return $data;
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
    ) {}
}

class PalmRecord
{
    public function __construct(
        public int $offset = 0,
        public int $attributes = 0,
        public int $id = 0
    ) {}
}

class MobiHeader
{
    public function __construct(
        public int $length = 0,
        public int $type = 0,
        public int $encoding = 0,
        public int $id = 0,
        public int $fileVersion = 0,
    ) {}
}

class ExthHeader
{
    /**
     * @param  ExthRecord[]  $records
     */
    public function __construct(
        public int $length = 0,
        public array $records = [],
    ) {}
}

class ExthRecord
{
    public function __construct(
        public int $type = 0,
        public int $length = 0,
        public ?string $data = null,
    ) {}
}
