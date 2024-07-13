<?php

namespace Kiwilan\Ebook\Utils;

class Stream
{
    protected function __construct(
        protected $path,
        protected $resource,
    ) {}

    /**
     * Creates a new instance of Stream.
     *
     * @throws \Exception
     */
    public static function make(string $path): self
    {
        $resource = fopen($path, 'rb');

        if (! $resource) {
            throw new \Exception("Unable to open file: {$path}");
        }

        return new self($path, $resource);
    }

    /**
     * Returns the file size in bytes.
     */
    public function filesize(): int
    {
        return filesize($this->path);
    }

    /**
     * Sets the file position indicator for the file pointer.
     */
    public function seek(int $offset): int
    {
        return fseek($this->resource, $offset, SEEK_SET);
    }

    /**
     * Reads a line from the file pointer.
     */
    public function read(int $bytes): string|false
    {
        return fread($this->resource, $bytes);
    }

    /**
     * Reads a 32-bit unsigned integer from the current position of the file read pointer.
     */
    public function binaryToDecimal($bytes): int|float
    {
        return hexdec(bin2hex($bytes));
    }

    /**
     * Returns the current position of the file read/write pointer.
     */
    public function tell(): int
    {
        return ftell($this->resource);
    }

    /**
     * Closes an open file pointer.
     */
    public function close(): bool
    {
        return fclose($this->resource);
    }

    /**
     * Get file path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get file resource.
     *
     * @return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }
}
