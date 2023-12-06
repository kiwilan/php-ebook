<?php

namespace Kiwilan\Ebook;

class EbookCover
{
    protected function __construct(
        protected ?string $path = null,
        protected ?string $contents = null,
    ) {
    }

    public static function make(?string $path = null, ?string $contents = null): ?self
    {
        if ($contents === null) {
            return null;
        }

        if (! EbookCover::isBase64($contents)) {
            $contents = base64_encode($contents);
        }

        return new self($path, $contents);
    }

    private static function isBase64(string $contents): bool
    {
        return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $contents);
    }

    /**
     * Get the cover path.
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @deprecated Use getContents() instead.
     */
    public function getContent(bool $toBase64 = false): ?string
    {
        return $this->getContents($toBase64);
    }

    /**
     * Get the cover contents.
     *
     * @param  bool  $toBase64 If true, the contents will be returned in base64 format.
     */
    public function getContents(bool $toBase64 = false): ?string
    {
        if ($this->contents === null) {
            return null;
        }

        if ($toBase64) {
            return $this->contents;
        }

        return base64_decode($this->contents);
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'contents' => $this->contents,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
