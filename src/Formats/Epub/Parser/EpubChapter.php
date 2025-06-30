<?php

namespace Kiwilan\Ebook\Formats\Epub\Parser;

/**
 * Merge `.ncx` and `.html` files to create chapters.
 */
class EpubChapter
{
    protected function __construct(
        protected ?string $label = null,
        protected ?string $source = null,
        protected ?string $content = null,
    ) {}

    /**
     * @param  EpubHtml[]  $html
     * @return EpubChapter[]
     */
    public static function toArray(?NcxItem $ncx, ?array $html): array
    {
        if (! $ncx || ! $html) {
            return [];
        }

        $chapters = [];
        foreach ($ncx->getNavPoints() as $item) {
            $htmlItem = self::findByFilename($html, $item->getSrc());

            if (! $htmlItem) {
                continue;
            }

            $chapters[] = new self(
                $item->getLabel(),
                $item->getSrc(),
                $htmlItem->getBody(),
            );
        }

        return $chapters;
    }

    /**
     * @param  EpubHtml[]  $html
     */
    private static function findByFilename(array $html, string $filename)
    {
        foreach ($html as $item) {
            if (str_contains($item->getFilename(), $filename)) {
                return $item;
            }
        }

        return false;
    }

    /**
     * Get the chapter label, e.g. `Chapter 1`
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get the source file name, e.g. `OEBPS/epub_c01_r1.htm`
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Get the chapter content, e.g. `<h1>Chapter 1</h1><p>Content...</p>`
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @deprecated Use `getLabel()` instead.
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * @deprecated Use `getSource()` instead.
     */
    public function source(): string
    {
        return $this->source;
    }

    /**
     * @deprecated Use `getContent()` instead.
     */
    public function content(): string
    {
        return $this->content;
    }
}
