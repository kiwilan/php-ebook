<?php

namespace Kiwilan\Ebook\Models;

class BookAuthor
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $role = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
        ];
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
