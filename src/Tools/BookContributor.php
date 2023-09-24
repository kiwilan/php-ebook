<?php

namespace Kiwilan\Ebook\Tools;

class BookContributor
{
    public function __construct(
        protected mixed $contents = null,
        protected ?string $role = null,
    ) {
        $this->contents = BookMeta::parse($this->contents);
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'contents' => $this->contents,
            'role' => $this->role,
        ];
    }

    public function __toString(): string
    {
        return $this->contents;
    }
}
