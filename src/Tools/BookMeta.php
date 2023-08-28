<?php

namespace Kiwilan\Ebook\Tools;

class BookMeta
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $content = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContent(): ?string
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

    public static function parse(mixed $data): ?string
    {
        if (is_string($data)) {
            return $data;
        }

        if (is_int($data) || is_bool($data) || is_float($data)) {
            return (string) $data;
        }

        if (is_array($data)) {
            if (array_key_exists('@attributes', $data)) {
                if (array_key_exists('scheme', $data['@attributes'])) {
                    return $data['@attributes']['scheme'] ?? null;
                }

                if (array_key_exists('content', $data['@attributes'])) {
                    return $data['@attributes']['content'] ?? null;
                }

                if (array_key_exists('role', $data['@attributes'])) {
                    return $data['@attributes']['role'] ?? null;
                }

                return json_encode($data['@attributes']);
            }

            return json_encode($data);
        }

        if (is_object($data)) {
            return json_encode($data);
        }

        return null;
    }
}
