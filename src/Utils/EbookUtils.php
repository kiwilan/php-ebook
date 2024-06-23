<?php

namespace Kiwilan\Ebook\Utils;

class EbookUtils
{
    /**
     * @return string[]|null|string|int
     */
    public static function parseStringWithSeperator(mixed $content): mixed
    {
        if (! $content) {
            return null;
        }

        if (! is_string($content)) {
            return $content;
        }

        if (str_contains($content, ', ')) {
            $content = explode(',', $content);
        } elseif (str_contains($content, ';')) {
            $content = explode(';', $content);
        } elseif (str_contains($content, ' & ')) {
            $content = explode('&', $content);
        } elseif (str_contains($content, ' and ')) {
            $content = explode('and', $content);
        } elseif (str_contains($content, '/')) {
            $content = explode('/', $content);
        } elseif (str_contains($content, '//')) {
            $content = explode('//', $content);
        } else {
            $content = [$content];
        }

        if (is_array($content)) {
            $content = array_map('trim', $content);
        }

        return $content;
    }

    public static function parseNumber(mixed $number): int|float|null
    {
        if (EbookUtils::isFloat($number)) {
            return floatval($number);
        }

        if (is_string($number)) {
            return intval($number);
        }

        if (is_int($number)) {
            return $number;
        }

        return null;
    }

    public static function isFloat(mixed $string): bool
    {
        if (is_numeric($string)) {
            $val = $string + 0;

            $is_float = is_float($val);
            if ($is_float) {
                $explode = explode('.', strval($val));
                $after_dot = $explode[1] ?? 0;
                if ($after_dot === 0) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }

    public static function limitLength(?string $string, int $length): ?string
    {
        if (! $string) {
            return null;
        }

        if (mb_strlen($string) <= $length) {
            return $string;
        }

        return mb_substr($string, 0, $length - 3).'…';
    }
}
