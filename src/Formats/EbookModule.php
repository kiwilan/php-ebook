<?php

namespace Kiwilan\Ebook\Formats;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;

abstract class EbookModule
{
    protected function __construct(
        protected Ebook $ebook,
    ) {}

    abstract public static function make(Ebook $ebook): self;

    abstract public function toEbook(): Ebook;

    abstract public function toCover(): ?EbookCover;

    abstract public function toCounts(): Ebook;

    abstract public function toArray(): array;

    protected function arrayToHtml(?array $array): ?string
    {
        if (! $array) {
            return null;
        }

        $html = '';
        foreach ($array as $tag => $item) {
            if (is_array($item)) {
                $html .= $this->arrayToHtml($item);
            } else {
                $html .= "<$tag>$item</$tag>";
            }
        }

        return $html;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
