<?php

namespace Kiwilan\Ebook\Models;

class BookIdentifier
{
    /**
     * @param  bool  $autoDetect  Try to auto detect scheme, even if provided (default: `true`)
     */
    public function __construct(
        protected mixed $value = null,
        protected ?string $scheme = null, // isbn10, isbn13, asin, etc.
        protected bool $autoDetect = true,
    ) {
        $this->value = BookMeta::parse($this->value);
        if ($this->autoDetect) {
            $this->scheme = $this->parseScheme($this->scheme);
        }
    }

    private function parseScheme(?string $scheme = null): string
    {
        $isValidInt = $this->value !== null && (is_int($this->value) || ctype_digit($this->value));
        $isValidIsbn = false;
        if ($isValidInt && $this->isIsbn()) {
            $scheme = $this->parseIsbn();
            $isValidIsbn = $scheme !== null;
        }

        if (! $isValidIsbn) {
            if ($this->isDoi()) {
                $scheme = 'doi';
            } elseif ($this->isUuid()) {
                $scheme = 'uuid';
            }
        }

        if (! $scheme) {
            if (str_contains($this->value, ':')) {
                $scheme = explode(':', $this->value)[0];
            } else {
                // assign default scheme
                $scheme = base64_encode($this->value);
            }
        }

        return strtolower($scheme);
    }

    private function isIsbn(): bool
    {
        $regex = '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/';
        if ($this->value !== null && preg_match($regex, $this->value, $matches)) {
            return true;
        }

        return false;
    }

    private function isDoi(): bool
    {
        $regex = '/^10.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i';
        if ($this->value !== null && preg_match($regex, $this->value, $matches)) {
            return true;
        }

        return false;
    }

    private function isUuid(): bool
    {
        $regex = '/^urn:uuid:([a-f\d]{8}(-[a-f\d]{4}){3}-[a-f\d]{12})$/i';
        if ($this->value !== null && preg_match($regex, $this->value, $matches)) {
            return true;
        }

        $regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        if ($this->value !== null && preg_match($regex, $this->value, $matches)) {
            return true;
        }

        return false;
    }

    private function parseIsbn(): ?string
    {
        $isbn = str_replace('-', '', $this->value);

        if (strlen($isbn) === 10) {
            return 'isbn10';
        }

        if (strlen($isbn) === 13) {
            return 'isbn13';
        }

        return null;
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
