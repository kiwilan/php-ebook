<?php

namespace Kiwilan\Ebook\Tools;

class BookMeta
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $content = null,
    ) {
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'content' => $this->content,
        ];
    }

    public function __toString(): string
    {
        return "{$this->name} {$this->content}";
    }
}
