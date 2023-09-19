<?php

namespace Kiwilan\Ebook;

class EbookCover
{
    protected function __construct(
        protected ?string $path = null,
        protected ?string $content = null,
    ) {
    }

    public static function make(string $path = null, string $content = null): ?self
    {
        if ($content === null) {
            return null;
        }

        if (! EbookCover::isBase64($content)) {
            $content = base64_encode($content);
        }

        return new self($path, $content);
    }

    private static function isBase64(string $content): bool
    {
        return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $content);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getContent(bool $toBase64 = false): ?string
    {
        if ($this->content === null) {
            return null;
        }

        if ($toBase64) {
            return $this->content;
        }

        return base64_decode($this->content);
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'content' => $this->content,
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
