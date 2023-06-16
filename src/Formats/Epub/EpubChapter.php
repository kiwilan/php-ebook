<?php

namespace Kiwilan\Ebook\Formats\Epub;

/**
 * Merge `.ncx` and `.html` files to create chapters.
 */
class EpubChapter
{
    protected function __construct(
        protected string $label,
        protected string $source,
        protected string $content,
    ) {
    }

    /**
     * @param  EpubHtml[]  $html
     * @return EpubChapter[]
     */
    public static function toArray(?NcxMetadata $ncx, ?array $html): array
    {
        if (! $ncx || ! $html) {
            return [];
        }

        $chapters = [];
        foreach ($ncx->navPoints() as $item) {
            $htmlItem = self::findByFilename($html, $item->src());

            if (! $htmlItem) {
                continue;
            }

            $chapters[] = new self(
                $item->label(),
                $item->src(),
                $htmlItem->body(),
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
            if ($filename == $item->filename()) {
                return $item;
            }
        }

        return false;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function content(): string
    {
        return $this->content;
    }
}
