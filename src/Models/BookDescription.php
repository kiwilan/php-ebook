<?php

namespace Kiwilan\Ebook\Models;

use Kiwilan\Ebook\Utils\EbookUtils;

/**
 * Advanced book description.
 */
class BookDescription
{
    protected function __construct(
        protected ?string $rawDescription = null,
    ) {
    }

    public static function make(?string $description): self
    {
        return new self($description);
    }

    /**
     * Get the raw description.
     *
     * @param  int|null  $limit  Limit the description length.
     */
    public function getDescription(?int $limit = null): ?string
    {
        return $this->parseLimit($this->rawDescription, $limit);
    }

    /**
     * Get the description as HTML (remove all tags and new lines except `div`, `p`, `br`, `b`, `i`, `u`, `strong`, `em`).
     *
     * @param  int|null  $limit  Limit the description length.
     */
    public function toHtml(?int $limit = null): ?string
    {
        $html = $this->sanitizeHtml($this->rawDescription);
        if (! $html) {
            return null;
        }

        if ($limit) {
            $html = EbookUtils::limitLength($html, $limit);
        }

        if ($html === strip_tags($html)) {
            $html = "<div>$html</div>";
        }

        return $html;
    }

    /**
     * Get the description as plain text (remove any HTML tags and new lines).
     *
     * @param  int|null  $limit  Limit the description length.
     */
    public function toString(?int $limit = null): ?string
    {
        if (! $this->rawDescription) {
            return null;
        }

        $text = $this->rawDescription;
        $text = strip_tags($text);
        $text = $this->cleanText($text);

        return $this->parseLimit($text, $limit);
    }

    /**
     * Get the description as multiline text (remove any HTML tags).
     *
     * @param  int|null  $limit  Limit the description length.
     */
    public function toStringMultiline(?int $limit = null): ?string
    {
        if (! $this->rawDescription) {
            return null;
        }

        $text = $this->rawDescription;
        $text = strip_tags($text);
        $text = $this->cleanText($text, removeNewlinesAndTabs: false);

        return $this->parseLimit($text, $limit);
    }

    private function parseLimit(string $text, ?int $limit): string
    {
        if (! $limit) {
            return $text;
        }

        return EbookUtils::limitLength($text, $limit);
    }

    /**
     * Sanitize HTML, remove all tags except div, p, br, b, i, u, strong, em.
     */
    private function sanitizeHtml(?string $html): ?string
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
        $html = $this->cleanText($html);

        return $html;
    }

    /**
     * Remove new lines and tabs from text.
     */
    private function removeNewlinesAndTabs(string $text): string
    {
        $text = str_replace("\n", '', $text); // remove new lines
        $text = str_replace("\r", '', $text); // remove carriage returns
        $text = str_replace("\t", '', $text); // remove tabs
        $text = preg_replace('/\s+/', ' ', $text); // remove multiple spaces
        $text = trim($text);

        return $text;
    }

    /**
     * Clean text from new lines, tabs, dots, and spaces (don't remove HTML tags).
     */
    private function cleanText(string $text, bool $removeNewlinesAndTabs = true, bool $cleanDotsAndSpaces = true, bool $removeSpacesBeforeTags = true): string
    {
        if ($removeNewlinesAndTabs) {
            $text = $this->removeNewlinesAndTabs($text);
        }

        if ($cleanDotsAndSpaces) {
            $text = $this->cleanDotsAndSpaces($text);
        }

        if ($removeSpacesBeforeTags) {
            $text = $this->removeSpacesBeforeTags($text);
        }

        return $text;
    }

    /**
     * Clean suspensive dots and remove dots without spaces.
     */
    private function cleanDotsAndSpaces(string $text): string
    {
        $text = str_replace('...', 'SUSPENSE_DOTS', $text);
        $text = preg_replace('/\.(?!\s)/', '. ', $text); // remove dot without space
        $text = str_replace('SUSPENSE_DOTS', '... ', $text);
        $text = trim($text);

        return $text;
    }

    private function removeSpacesBeforeTags(string $text): string
    {
        if ($text !== strip_tags($text)) {
            $text = preg_replace('/\s+</', '<', $text); // remove spaces before tags
        }

        return $text;
    }
}
