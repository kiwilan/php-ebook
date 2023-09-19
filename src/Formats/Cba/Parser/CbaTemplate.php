<?php

namespace Kiwilan\Ebook\Formats\Cba\Parser;

use Kiwilan\XmlReader\XmlReader;

abstract class CbaTemplate
{
    abstract public static function make(XmlReader $xml): self;

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
