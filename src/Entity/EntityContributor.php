<?php

namespace Kiwilan\Ebook\Entity;

class EntityContributor
{
    public function __construct(
        protected ?string $content = null,
        protected ?string $role = null,
    ) {
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function role(): ?string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role,
        ];
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
