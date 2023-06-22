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
        protected ?string $slugSort = null,
        protected ?string $slugLang = null,

        protected ?string $serieSlug = null,
        protected ?string $serieSlugSort = null,
        protected ?string $serieSlugLang = null,

        protected ?string $slugSortWithSerie = null,
        protected ?string $uniqueFilename = null,
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
    ): self {
        $self = new self();

        $self->determiners = $determiners;
        $self->setMetaTitle($ebook);

        return $self;
    }

    private function setMetaTitle(Ebook $ebook): static
    {
        $title = $ebook->title();
        $language = $ebook->language();
        $series = $ebook->series();
        $volume = $ebook->volume();

        if (! $title) {
            return $this;
        }

        $this->slug = $this->setSlug($title);
        $this->slugSort = $this->generateSortTitle($title, $language);
        $this->slugLang = $this->generateSlug($title, $ebook->extension(), $language);

        $this->slugSortWithSerie = $this->generateSortSerie($title, $series, $volume, $language);
        $this->uniqueFilename = $this->generateUniqueFilename($ebook);

        if (! $series) {
            return $this;
        }

        $this->serieSlug = $this->setSlug($series);
        $this->serieSlugSort = $this->generateSortTitle($series, $language);
        $this->serieSlugLang = $this->generateSlug($series, $ebook->extension(), $language);

        return $this;
    }

    /**
     * Get slug of book title, like `the-clan-of-the-cave-bear`.
     */
    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * Get slug of book title without determiners, like `clan-of-the-cave-bear`.
     */
    public function slugSort(): string
    {
        return $this->slugSort;
    }

    /**
     * Get slug of book title with language and with type, like `the-clan-of-the-cave-bear-epub-en`.
     */
    public function slugLang(): string
    {
        return $this->slugLang;
    }

    /**
     * Get slug of serie title, like `earths-children`.
     */
    public function serieSlug(): ?string
    {
        return $this->serieSlug;
    }

    /**
     * Get slug of serie title without determiners, like `earths-children`.
     */
    public function serieSlugSort(): ?string
    {
        return $this->serieSlugSort;
    }

    /**
     * Get slug of serie title with language and with type, like `earths-children-epub-en`.
     */
    public function serieSlugLang(): ?string
    {
        return $this->serieSlugLang;
    }

    /**
     * Get slug of book title with serie title, like `earths-children-01_clan-of-the-cave-bear`.
     * If series is null, book's title will be used like `clan-of-the-cave-bear`.
     */
    public function slugSortWithSerie(): string
    {
        return $this->slugSortWithSerie;
    }

    /**
     * Get unique filename, like `jean-m-auel-earths-children-01-clan-of-the-cave-bear-en-epub`.
     */
    public function uniqueFilename(): string
    {
        return $this->uniqueFilename;
    }

    /**
     * Try to get sort title.
     * Example: `collier-de-la-reine` from `Le Collier de la Reine`.
     */
    private function generateSortTitle(?string $title, ?string $language): ?string
    {
        if (! $title) {
            return null;
        }

        $slugSort = $title;
        $articles = $this->determiners;

        $articlesLang = $articles['en'];

        if ($language && array_key_exists($language, $articles)) {
            $articlesLang = $articles[$language];
        }

        foreach ($articlesLang as $key => $value) {
            $slugSort = preg_replace('/^'.preg_quote($value, '/').'/i', '', $slugSort);
        }

        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $slugSort = $transliterator->transliterate($slugSort);
        $slugSort = strtolower($slugSort);

        return $this->setSlug(mb_convert_encoding($slugSort, 'UTF-8'));
    }

    /**
     * Generate full title sort.
     * Example: `miserables-01_fantine` from `Les Misérables, volume 01 : Fantine`.
     */
    private function generateSortSerie(string $title, ?string $serieTitle, ?int $volume, ?string $language): string
    {
        $serie = null;

        if ($serieTitle) {
            $volume = (string) $volume;
            $volume = strlen($volume) < 2 ? '0'.$volume : $volume;
            $serie = $serieTitle.' '.$volume;
            $serie = $this->setSlug($this->generateSortTitle($serie, $language)).'_';
        }
        $title = $this->setSlug($this->generateSortTitle($title, $language));

        return "{$serie}{$title}";
    }

    /**
     * Generate `slug` with `title`, `type` and `language`.
     */
    private function generateSlug(string $title, ?string $type, ?string $language): string
    {
        $title = $this->setSlug($title);
        $type = $this->setSlug($type);
        $language = $this->setSlug($language);

        return $this->setSlug($title.' '.$type.' '.$language);
    }

    /**
     * Generate unique filename.
     */
    private function generateUniqueFilename(Ebook $ebook): string
    {
        $language = $this->setSlug($ebook->language());
        $filename = "{$language}";
        if ($ebook->series()) {
            $series = $this->setSlug($ebook->series());
            $filename .= "-{$series}";
        }
        if ($ebook->volume()) {
            $volume = (string) $ebook->volume();
            $volume = $volume = strlen($volume) < 2 ? '0'.$volume : $volume;
            $filename .= "-{$volume}";
        }
        $title = $this->setSlug($ebook->title());
        $filename .= "-{$title}";
        $author = $this->setSlug($ebook->authorMain());
        $filename .= "-{$author}";
        $format = $this->setSlug($ebook->extension());
        $filename .= "-{$format}";

        $filename = $this->setSlug($filename);

        return $filename;
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'slugSort' => $this->slugSort,
            'slugLang' => $this->slugLang,

            'serieSlug' => $this->serieSlug,
            'serieSlugSort' => $this->serieSlugSort,
            'serieSlugLang' => $this->serieSlugLang,

            'slugSortWithSerie' => $this->slugSortWithSerie,
        ];
    }

    public function __toString(): string
    {
        return "{$this->slug} {$this->slugSort}";
    }

    /**
     * Laravel export.
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  array<string, string>  $dictionary
     */
    private function setSlug(?string $title, string $separator = '-', array $dictionary = ['@' => 'at']): ?string
    {
        if (! $title) {
            return null;
        }

        if (! extension_loaded('intl')) {
            return $this->setSlugNoIntl($title, $separator);
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

    private function setSlugNoIntl(?string $text, string $divider = '-'): ?string
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
