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

    protected function toHtml(?string $html): ?string
    {
        $html = $this->sanitizeHtml($html);
        if (! $html) {
            return null;
        }

        if ($html === strip_tags($html)) {
            return "<div>$html</div>";
        }

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

    protected function descriptionToString(?string $description): ?string
    {
        if (! $description) {
            return null;
        }

        $description = $this->htmlToString($description);

        return $description;
    }

    protected function descriptionToHtml(?string $description): ?string
    {
        if (! $description) {
            return null;
        }

        $description = $this->toHtml($description);

        if ($description === strip_tags($description)) {
            $description = "<div>$description</div>";
        }

        return $description;
    }

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

    /**
     * Generate `created_at` from file modified time.
     */
    protected function generateCreatedAt(): void
    {
        $file = new \SplFileInfo($this->ebook->getpath());
        if ($file->getMTime()) {
            $ts = gmdate("Y-m-d\TH:i:s\Z", $file->getMTime());
            $dt = new \DateTime($ts);
            $this->ebook->setCreatedAt($dt);
        }
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
