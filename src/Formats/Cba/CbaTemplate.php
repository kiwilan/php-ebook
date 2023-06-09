<?php

namespace Kiwilan\Ebook\Formats\Cba;

abstract class CbaTemplate
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    abstract public static function make(array $metadata): self;

    /**
     * @return string[]
     */
    protected function arrayable(?string $value): array
    {
        if (! $value) {
            return [];
        }

        $value = trim($value);
        $value = str_replace(';', ',', $value);
        $value = explode(',', $value);

        return array_map(fn ($v) => trim($v), $value);
    }
}
