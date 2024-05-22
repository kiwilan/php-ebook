<?php

namespace Kiwilan\Ebook\Utils;

class EbookUtils
{
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
}
