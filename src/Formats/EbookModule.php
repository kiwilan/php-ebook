<?php

namespace Kiwilan\Ebook\Formats;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;

abstract class EbookModule
{
    protected function __construct(
        protected Ebook $ebook,
    ) {
    }

    abstract public static function make(Ebook $ebook): self;

    abstract public function toEbook(): Ebook;

    abstract public function toCover(): ?EbookCover;

    abstract public function toCounts(): Ebook;

    abstract public function toArray(): array;

    /**
     * Convert HTML to string, remove all tags.
     */
    protected function htmlToString(?string $html): ?string
    {
        if (! $html) {
            return null;
        }

        $html = strip_tags($html);
        $html = $this->formatText($html);

        return $html;
    }

    /**
     * Sanitize HTML, remove all tags except div, p, br, b, i, u, strong, em.
     */
    protected function sanitizeHtml(?string $html): ?string
    {
        if (! $html) {
            return null;
        }

        $html = strip_tags($html, [
            'div',
            'p',
            'br',
            'b',
            'i',
            'u',
            'strong',
            'em',
        ]);
        $html = $this->formatText($html);

        return $html;
    }

    /**
     * Clean string, remove tabs, new lines, carriage returns, and multiple spaces.
     */
    private function formatText(string $text): string
    {
        $text = str_replace("\n", '', $text); // remove new lines
        $text = str_replace("\r", '', $text); // remove carriage returns
        $text = str_replace("\t", '', $text); // remove tabs
        $text = trim($text);

        $text = str_replace('...', 'SUSPENSE_DOTS', $text);
        $text = preg_replace('/\.(?!\s)/', '. ', $text); // remove dot without space
        $text = str_replace('SUSPENSE_DOTS', '... ', $text);
        $text = preg_replace('/\s+/', ' ', $text); // remove multiple spaces
        $text = trim($text);

        if ($text !== strip_tags($text)) {
            $text = preg_replace('/\s+</', '<', $text); // remove spaces before tags
        }

        return $text;
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
