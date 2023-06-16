<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

class StreamParser
{
    /**
     * @param  resource  $stream
     */
    protected function __construct(
        protected mixed $stream
    ) {
    }

    /**
     * Opens file or URL.
     */
    public static function make(string $path, string $mode = 'r'): StreamParser
    {
        $stream = fopen($path, $mode);

        if (! $stream) {
            throw new \Exception("Cannot open file: {$path}");
        }

        $self = new self($stream);

        return $self;
    }

    /**
     * Seeks on a file pointer.
     */
    public function seek(int $offset, int $whence = SEEK_SET): int
    {
        return fseek($this->stream, $offset, $whence);
    }

    /**
     * Returns the current position of the file read/write pointer.
     */
    public function tell(): int
    {
        return ftell($this->stream);
    }

    /**
     * Binary-safe file read.
     *
     * @param  int[]|string[]|int|string  $bytes
     */
    public function read(mixed $bytes): string|false
    {
        if (is_int($bytes) || is_string($bytes)) {
            $bytes = [$bytes];
        }

        $content = '';
        foreach ($bytes as $byte) {
            $byte = (int) $byte;
            if ($byte > 0) {
                $content = fread($this->stream, $byte);
            }
        }

        return $content;
    }

    /**
     * Read binary data from stream, convert to decimal.
     *
     * @param  int[]|string[]|int|string  $bytes
     */
    public function toInt(mixed $bytes): int|float
    {
        $content = $this->read($bytes);
        $hexa = bin2hex($content);

        return hexdec($hexa);
    }

    /**
     * Add error to `error_log`.
     */
    public function errorLog(string $error): string
    {
        error_log($error);

        return $error;
    }

    /**
     * Closes an open file pointer.
     */
    public function close(): bool
    {
        return fclose($this->stream);
    }
}
