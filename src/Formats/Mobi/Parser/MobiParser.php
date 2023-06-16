<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

/**
 * Inspired by https://stackoverflow.com/a/15199117/11008206
 */
class MobiParser
{
    protected ?MobiPalmDOCHeader $docHeader = null;

    protected ?MobiHeader $mobiHeader = null;

    protected ?MobiExthHeader $exthHeader = null;

    protected ?MobiReader $reader = null;

    /** @var MobiExthRecord[] */
    protected array $records = [];

    /** @var MobiPalmRecord[] */
    protected array $palmHeaders = [];

    /** @var string[] */
    protected array $errors = [];

    protected function __construct(
        protected string $path
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self($path);
        $stream = StreamParser::make($self->path);

        $stream->seek(60);
        $bookmobi = $stream->read(8);

        if ($bookmobi !== 'BOOKMOBI') {
            $self->errors[] = $stream->errorLog('Invalid file format');
            $stream->close();

            return $self;
        }

        $stream->seek(0);
        $name = $stream->read(32);
        $stream->seek(76);
        $records = $stream->toInt(2);
        $stream->seek(78);

        for ($i = 0; $i < $records; $i++) {
            $record = MobiPalmRecord::make($stream);
            $self->palmHeaders[] = $record;
        }

        $self->docHeader = MobiPalmDOCHeader::make($stream, $self->palmHeaders);

        $mobiStart = $stream->tell();
        $mobi = $stream->read(4);

        if ($mobi !== 'MOBI') {
            $self->errors[] = $stream->errorLog('No MOBI header');
            $stream->close();

            return $self;
        }

        $self->mobiHeader = MobiHeader::make($stream);

        $stream->seek($mobiStart + $self->mobiHeader->length());
        $exth = $stream->read(4);

        if ($exth !== 'EXTH') {
            $self->errors[] = $stream->errorLog('No EXTH header');
            $stream->close();

            return $self;
        }

        $self->exthHeader = MobiExthHeader::make($stream);
        $self->records = $self->exthHeader->records();
        $self->reader = MobiReader::make($stream, $self->exthHeader->records());

        $stream->close();

        $self->cover();

        return $self;
    }

    private function cover(): self
    {
        $stream = StreamParser::make($this->path, 'rb');

        // $handle = fopen($this->ebook->path(), 'rb');
        // if (! $handle) {
        //     return;
        // }

        // $record = array_filter($this->records, fn ($e) => $e->type() === 201);
        // $record = reset($record);

        // $command = "exiftool -json -charset UTF8 {$this->path}";
        // $output = shell_exec($command);

        // $metadata = json_decode($output, true);

        // if (! empty($metadata)) {
        //     ray($metadata);
        // }

        // $command = "exiftool -b -CoverImage {$this->path}";
        // $output = shell_exec($command);
        // ray($output);

        // $coverPath = 'chemin/vers/votre/cover.jpg';
        // file_put_contents($coverPath, $output);

        return $this;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function docHeader(): ?MobiPalmDOCHeader
    {
        return $this->docHeader;
    }

    public function mobiHeader(): ?MobiHeader
    {
        return $this->mobiHeader;
    }

    public function exthHeader(): ?MobiExthHeader
    {
        return $this->exthHeader;
    }

    public function reader(): ?MobiReader
    {
        return $this->reader;
    }

    /**
     * @return MobiExthRecord[]
     */
    public function records(): array
    {
        return $this->records;
    }

    /**
     * @return MobiPalmRecord[]
     */
    public function palmHeaders(): array
    {
        return $this->palmHeaders;
    }

    /**
     * @return string[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function errorLog(): string
    {
        return implode("\n", $this->errors);
    }
}
