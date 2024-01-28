<?php

namespace Kiwilan\Ebook\Models;

class BookMeta
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $contents = null,
    ) {
    }

    /**
     * Get the meta name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @deprecated Use getContents() instead
     */
    public function getContent(): ?string
    {
        return $this->contents;
    }

    /**
     * Get the meta contents.
     */
    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'contents' => $this->contents,
        ];
    }

    public function __toString(): string
    {
        return "{$this->name} {$this->contents}";
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

                if (array_key_exists('contents', $data['@attributes'])) {
                    return $data['@attributes']['contents'] ?? null;
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
