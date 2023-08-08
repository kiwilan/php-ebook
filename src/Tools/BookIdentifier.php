<?php

namespace Kiwilan\Ebook\Tools;

class BookIdentifier
{
    public function __construct(
        protected mixed $value = null,
        protected ?string $scheme = null,
    ) {
        $this->value = BookMeta::parse($this->value);
    }

    public function parse(): self
    {
        $this->scheme = $this->parseScheme();

        return $this;
    }

    private function parseScheme(): ?string
    {
        if (! $this->scheme) {
            return null;
        }

        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';

        if (preg_match($regex, str_replace('-', '', $this->value), $matches)) {
            return (10 === strlen($matches[1]))
                ? 'isbn10'
                : 'isbn13';
        }

        return strtolower($this->scheme);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'scheme' => $this->scheme,
        ];
    }

    public function __toString(): string
    {
        return "{$this->value} {$this->scheme}";
    }
}
