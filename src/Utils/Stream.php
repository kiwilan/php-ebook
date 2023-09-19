<?php

namespace Kiwilan\Ebook\Utils;

class Stream
{
    protected function __construct(
        protected $path,
        protected $resource,
    ) {
    }

    public static function make(string $path): self
    {
        $resource = fopen($path, 'rb');

        if (! $resource) {
            throw new \Exception("Unable to open file: {$path}");
        }

        return new self($path, $resource);
    }

    public function seek(int $offset): int
    {
        return fseek($this->resource, $offset, SEEK_SET);
    }

    public function read(int $bytes): string|false
    {
        return fread($this->resource, $bytes);
    }

    public function binaryToDecimal($bytes): int|float
    {
        return hexdec(bin2hex($bytes));
    }

    public function tell(): int
    {
        return ftell($this->resource);
    }

    public function close(): bool
    {
        return fclose($this->resource);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }
}
