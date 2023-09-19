<?php

namespace Kiwilan\Ebook\Tools;

class BookIdentifier
{
    public function __construct(
        protected mixed $value = null,
        protected ?string $scheme = null, // isbn10, isbn13, asin, etc.
    ) {
        $this->value = BookMeta::parse($this->value);

        $scheme = $this->parseScheme();
        $this->scheme = $scheme ?? $this->scheme;
    }

    private function parseScheme(): ?string
    {
        if ($this->scheme === null) {
            $regex = '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/';
            if (preg_match($regex, $this->value, $matches)) {
                $isbn = str_replace('-', '', $matches[0]);

                return (strlen($isbn) === 10)
                    ? 'isbn10'
                    : 'isbn13';
            }

            return null;
        }

        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';

        if (preg_match($regex, str_replace('-', '', $this->value), $matches)) {
            return (strlen($matches[1]) === 10)
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
