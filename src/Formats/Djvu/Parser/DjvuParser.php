<?php

namespace Kiwilan\Ebook\Formats\Djvu\Parser;

class DjvuParser
{
    protected function __construct(
        protected string $path,
    ) {
    }

    public static function make(string $path): DjvuParser
    {
        $self = new self($path);

        return $self;
    }
}
