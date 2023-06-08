<?php

namespace Kiwilan\Ebook\Tools;

class BookIdentifier
{
    public function __construct(
        protected ?string $content = null,
        protected ?string $type = null,
    ) {
    }

    public function parse(): self
    {
        $this->type = $this->parseType();

        return $this;
    }

    private function parseType(): ?string
    {
        if (! $this->type) {
            return null;
        }

        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';

        if (preg_match($regex, str_replace('-', '', $this->content), $matches)) {
            return (10 === strlen($matches[1]))
                ? 'isbn10'
                : 'isbn13';
        }

        return strtolower($this->type);
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'type' => $this->type,
        ];
    }

    public function __toString(): string
    {
        return "{$this->content} {$this->type}";
    }
}
