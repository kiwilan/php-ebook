<?php

namespace Kiwilan\Ebook\Tools;

class BookContributor
{
    public function __construct(
        protected mixed $content = null,
        protected ?string $role = null,
    ) {
        $this->content = BookMeta::parse($this->content);
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
