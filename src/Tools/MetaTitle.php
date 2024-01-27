<?php

namespace Kiwilan\Ebook\Tools;

use Kiwilan\Ebook\Ebook;
use Transliterator;

class MetaTitle
{
    /** @var string[][] */
    protected array $determiners = [];

    protected function __construct(
        protected ?string $slug = null,
        protected ?string $slugSimple = null,
        protected ?string $seriesSlug = null,
        protected ?string $seriesSlugSimple = null,
    ) {
    }

    public static function make(
        Ebook $ebook,
        array $determiners = [
            'en' => [
                'the ',
                'a ',
            ],
            'fr' => [
                'les ',
                "l'",
                'le ',
                'la ',
                "d'un",
                "d'",
                'une ',
                'au ',
            ],
        ],
    ): ?self {
        if (! $ebook->getTitle()) {
            return null;
        }

        $self = new self();

        $self->determiners = $determiners;
        $self->parse($ebook);

        return $self;
    }

    private function parse(Ebook $ebook): static
    {
        $title = $this->generateSlug($ebook->getTitle());
        $language = $ebook->getLanguage() ? $this->generateSlug($ebook->getLanguage()) : null;
        $series = $ebook->getSeries() ? $this->generateSlug($ebook->getSeries()) : null;
        $volume = $ebook->getVolume() ? str_pad((string) $ebook->getVolume(), 2, '0', STR_PAD_LEFT) : null;
        $author = $ebook->getAuthorMain()?->getName() ? $this->generateSlug($ebook->getAuthorMain()->getName()) : null;
        $year = $ebook->getPublishDate()?->format('Y') ? $this->generateSlug($ebook->getPublishDate()->format('Y')) : null;
        $extension = strtolower($ebook->getExtension());

        $titleDeterminer = $this->removeDeterminers($ebook->getTitle(), $ebook->getLanguage());
        $seriesDeterminer = $this->removeDeterminers($ebook->getSeries(), $ebook->getLanguage());

        if (! $title) {
            return $this;
        }

        $this->slug = $this->generateSlug([
            $titleDeterminer,
            $series,
            $volume,
            $year,
            $author,
            $extension,
            $language,
        ]);
        $this->slugSimple = $this->generateSlug([$title]);

        $this->seriesSlug = $this->generateSlug([
            $seriesDeterminer,
            $year,
            $author,
            $extension,
            $language,
        ]);
        $this->seriesSlugSimple = $this->generateSlug([$seriesDeterminer]);

        return $this;
    }

    /**
     * Get slug of book title with addional metadata, like `pale-lumiere-des-tenebres-a-comme-association-01-pierre-bottero-epub-fr`.
     *
     * - Remove determiners, here `la`
     * - Add serie title, here `A comme Association`
     * - Add volume, here `1`
     * - Add author name, here `Pierre Bottero`
     * - Add extension, here `epub`
     * - Add language, here `fr`
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get simple slug of book title, like `la-pale-lumiere-des-tenebres`.
     */
    public function getSlugSimple(): string
    {
        return $this->slugSimple;
    }

    /**
     * Get slug of serie title, like `a-comme-association-pierre-bottero-epub-fr`.
     *
     * - Remove determiners
     * - Add author name
     * - Add extension
     * - Add language
     */
    public function getSeriesSlug(): ?string
    {
        return $this->seriesSlug;
    }

    /**
     * Get simple slug of serie title, like `a-comme-association`.
     */
    public function getSeriesSlugSimple(): ?string
    {
        return $this->seriesSlugSimple;
    }

    /**
     * @deprecated Use `getSlug()` instead.
     */
    public function getSlugSort(): string
    {
        return $this->slug;
    }

    /**
     * @deprecated Use `getSlug()` instead.
     */
    public function getSlugUnique(): string
    {
        return $this->slug;
    }

    /**
     * @deprecated Use `getSeriesSlugSimple()` instead.
     */
    public function getSerieSlug(): ?string
    {
        return $this->seriesSlugSimple;
    }

    /**
     * @deprecated Use `getSeriesSlug()` instead.
     */
    public function getSerieSlugSort(): ?string
    {
        return $this->seriesSlug;
    }

    /**
     * @deprecated Use `getSeriesSlug()` instead.
     */
    public function getSerieSlugUnique(): ?string
    {
        return $this->seriesSlug;
    }

    /**
     * @deprecated Use `getSlug()` instead.
     */
    public function getSlugSortWithSerie(): string
    {
        return $this->slug;
    }

    /**
     * @deprecated Use `getSlug()` instead.
     */
    public function getUniqueFilename(): string
    {
        return $this->slug;
    }

    private function removeDeterminers(?string $string, ?string $language): ?string
    {
        if (! $string) {
            return null;
        }

        $articles = $this->determiners;

        $articlesLang = $articles['en'];

        if ($language && array_key_exists($language, $articles)) {
            $articlesLang = $articles[$language];
        }

        foreach ($articlesLang as $key => $value) {
            $string = preg_replace('/^'.preg_quote($value, '/').'/i', '', $string);
        }

        return $string;
    }

    /**
     * Generate `slug` with params.
     *
     * @param  string[]|null[]|string  $strings
     */
    private function generateSlug(array|string $strings): ?string
    {
        if (! is_array($strings)) {
            $strings = [$strings];
        }

        $items = [];

        foreach ($strings as $string) {
            if (! $string) {
                continue;
            }

            $items[] = $this->slugifier($string);
        }

        return $this->slugifier(implode('-', $items));
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'slugSimple' => $this->slugSimple,
            'seriesSlug' => $this->seriesSlug,
            'seriesSlugSimple' => $this->seriesSlugSimple,
        ];
    }

    public function __toString(): string
    {
        return "{$this->slug}";
    }

    /**
     * Laravel export.
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  array<string, string>  $dictionary
     */
    private function slugifier(?string $title, string $separator = '-', array $dictionary = ['@' => 'at']): ?string
    {
        if (! $title) {
            return null;
        }

        if (! extension_loaded('intl')) {
            return $this->slugifierNative($title, $separator);
        }

        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $title = $transliterator->transliterate($title);

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace dictionary words
        foreach ($dictionary as $key => $value) {
            $dictionary[$key] = $separator.$value.$separator;
        }

        $title = str_replace(array_keys($dictionary), array_values($dictionary), $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    private function slugifierNative(?string $text, string $divider = '-'): ?string
    {
        if (! $text) {
            return null;
        }

        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
