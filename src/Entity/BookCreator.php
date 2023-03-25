<?php

namespace Kiwilan\Ebook\Entity;

class BookCreator
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $role = null,
    ) {
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function role(): ?string
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
