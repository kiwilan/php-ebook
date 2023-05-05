<?php

namespace Kiwilan\Ebook\Entity;

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
        $book = $ebook->book();

        if (! $book->title()) {
            return $this;
        }

        $this->slug = $this->setSlug($book->title());
        $this->slugSort = $this->generateSortTitle($book->title(), $book->language());
        $this->slugLang = $this->generateSlug($book->title(), $ebook->extension(), $book->language());

        $this->slugSortWithSerie = $this->generateSortSerie($book->title(), $book->series(), $book->volume(), $book->language());

        if (! $book->series()) {
            return $this;
        }

        $this->serieSlug = $this->setSlug($book->series());
        $this->serieSlugSort = $this->generateSortTitle($book->series(), $book->language());
        $this->serieSlugLang = $this->generateSlug($book->series(), $ebook->extension(), $book->language());

        return $this;
    }

    /**
     * Get slug of book title, like `le-clan-de-lours-des-cavernes`.
     */
    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * Get slug of book title without determiners, like `clan-de-lours-des-cavernes`.
     */
    public function slugSort(): string
    {
        return $this->slugSort;
    }

    /**
     * Get slug of book title with language, like `le-clan-de-lours-des-cavernes-epub-fr`.
     */
    public function slugLang(): string
    {
        return $this->slugLang;
    }

    /**
     * Get slug of serie title, like `les-enfants-de-la-terre`.
     */
    public function serieSlug(): ?string
    {
        return $this->serieSlug;
    }

    /**
     * Get slug of serie title without determiners, like `enfants-de-la-terre`.
     */
    public function serieSlugSort(): ?string
    {
        return $this->serieSlugSort;
    }

    /**
     * Get slug of serie title with language, like `les-enfants-de-la-terre-epub-fr`.
     */
    public function serieSlugLang(): ?string
    {
        return $this->serieSlugLang;
    }

    /**
     * Get slug of book title with serie title, like `enfants-de-la-terre-01_clan-de-lours-des-cavernes`.
     * If series is null, book's title will be used like `clan-de-lours-des-cavernes`.
     */
    public function slugSortWithSerie(): string
    {
        return $this->slugSortWithSerie;
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
            // @phpstan-ignore-next-line
            $volume = strlen($volume) < 2 ? '0'.$volume : $volume;
            $serie = $serieTitle.' '.$volume;
            $serie = $this->setSlug($this->generateSortTitle($serie, $language)).'_';
        }
        $title = $this->setSlug($this->generateSortTitle($title, $language));

        return "{$serie}{$title}";
    }

    /**
     * Generate `slug` with `title`,  `BookTypeEnum` and `language_slug`.
     */
    private function generateSlug(string $title, ?string $type, ?string $language): string
    {
        return $this->setSlug($title.' '.$type.' '.$language);
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
}
