<?php

namespace Kiwilan\Ebook;

class EbookCover
{
    protected function __construct(
        protected ?string $path = null,
        protected ?string $content = null,
    ) {
    }

    public static function make(?string $path, ?string $content): ?self
    {
        if ($content === null) {
            return null;
        }

        $content = base64_encode($content);

        return new self($path, $content);
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
