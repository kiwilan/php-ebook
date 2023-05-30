<?php

namespace Kiwilan\Ebook\Epub;

class EpubHtml
{
    protected string $filename;

    protected ?string $head = null;

    protected ?string $body = null;

    public static function make(string $html, string $filename): self
    {
        $self = new self();

        $self->filename = $filename;
        $self->head = $self->getTag($html, 'head');
        $self->body = $self->getTag($html, 'body');

        return $self;
    }

    private function getTag(string $html, string $tag): string
    {
        preg_match('/<'.$tag.'[^>]*>(.*?)<\/'.$tag.'>/is', $html, $matches);
        if (array_key_exists(1, $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function head(): ?string
    {
        return $this->head;
    }

    public function body(): ?string
    {
        return $this->body;
    }

    public function toArray(): array
    {
        return [
            'head' => $this->head,
            'body' => $this->body,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
