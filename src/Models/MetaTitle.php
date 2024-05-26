<?php

namespace Kiwilan\Ebook\Models;

use Kiwilan\Ebook\Ebook;
use Transliterator;

/**
 * Convert eBook title and metadata to a slug.
 *
 * @method string getSlug() Get slug of book title with addional metadata, like `lord-of-the-rings-01-fellowship-of-the-ring-j-r-r-tolkien-1954-epub-en`.
 * @method string getSlugSimple() Get simple slug of book title, like `the-fellowship-of-the-ring`.
 * @method string getSeriesSlug() Get slug of serie title, like `lord-of-the-rings-j-r-r-tolkien-epub-en`.
 * @method string getSeriesSlugSimple() Get simple slug of serie title, like `the-lord-of-the-rings`.
 */
class MetaTitle
{
    /**
     * @var string[][]
     */
    public const DETERMINERS = [
        'en' => [
            'the ',
            'a ',
            'an ',
            'some ',
            'any ',
            'this ',
            'that ',
            'my ',
            'your ',
            'his ',
            'her ',
            'its ',
            'our ',
            'their ',
            'all ',
            'both ',
            'each ',
        ],
        'fr' => [
            'les ',
            'l\'',
            'le ',
            'la ',
            'du ',
            'de ',
            'une ',
            'au ',
            'des ',
            'ce ',
            'cet ',
            'cette ',
            'ces ',
            'mon ',
            'ton ',
            'son ',
            'notre ',
            'votre ',
            'leur ',
            'tous ',
            'toutes ',
            'chaque ',
        ],
        'es' => [
            'el ',
            'la ',
            'los ',
            'las ',
            'un ',
            'una ',
            'este ',
            'esta ',
            'estos ',
            'estas ',
            'mi ',
            'tu ',
            'su ',
            'nuestro ',
            'vuestro ',
            'sus ',
            'mío ',
            'tuyo ',
            'suyo ',
            'algunos ',
            'algunas ',
            'todo ',
            'toda ',
            'todos ',
            'todas ',
            'otro ',
            'otra ',
        ],
        'it' => [
            'il ',
            'la ',
            'i ',
            'gli ',
            'le ',
            'un ',
            'uno ',
            'una ',
            'alcuni ',
            'alcune ',
            'questo ',
            'questa ',
            'questi ',
            'queste ',
            'quel ',
            'quella ',
            'quelli ',
            'quelle ',
            'mia ',
            'tua ',
            'sua ',
            'nostra ',
            'vostra ',
            'loro ',
            'ogni ',
            'tutti ',
            'tutte ',
            'alcuni ',
            'alcune ',
            'qualche ',
        ],
        'de' => [
            'der ',
            'die ',
            'das ',
            'ein ',
            'eine ',
            'mein ',
            'dein ',
            'sein ',
            'ihr ',
            'unser ',
            'euer ',
            'ihr ',
            'jeder ',
            'jede ',
            'jedes ',
            'alle ',
            'viel ',
            'einige ',
            'ein paar ',
            'manche ',
            'welcher ',
            'welche ',
            'welches ',
        ],
        'pl' => [
            'ten ',
            'ta ',
            'to ',
            'te ',
            'tamten ',
            'tamta ',
            'tamto ',
            'jaki ',
            'jaka ',
            'jakie ',
            'każdy ',
            'każda ',
            'każde ',
            'wszystki ',
            'wszystko ',
            'wszyscy ',
            'wszystkie ',
            'który ',
            'która ',
            'które ',
            'którzy ',
            'której ',
            'którego ',
            'którym ',
        ],
        'ru' => [
            'этот ',
            'эта ',
            'это ',
            'эти ',
            'тот ',
            'та ',
            'то ',
            'те ',
            'весь ',
            'вся ',
            'всё ',
            'все ',
            'каждый ',
            'каждая ',
            'каждое ',
            'каждые ',
            'мой ',
            'моя ',
            'моё ',
            'мои ',
            'твой ',
            'твоя ',
            'твоё ',
            'твои ',
            'свой ',
            'своя ',
            'своё ',
            'свои ',
            'наш ',
            'наша ',
            'наше ',
            'наши ',
            'ваш ',
            'ваша ',
            'ваше ',
            'ваши ',
            'их ',
            'их ',
            'некоторые ',
            'всякий ',
            'любой ',
            'каждый ',
        ],
        'zh' => [
            '这 ',
            '那 ',
            '一个 ',
            '这些 ',
            '那些 ',
        ],
        'ja' => [
            'これ ',
            'それ ',
            'あれ ',
            'この ',
            'その ',
            'あの ',
        ],
        'ko' => [
            '이 ',
            '그 ',
            '저 ',
            '이것 ',
            '그것 ',
            '저것 ',
        ],
        'ar' => [
            'هذا ',
            'هذه ',
            'ذلك ',
            'تلك ',
            'هؤلاء ',
            'تلكم ',
        ],
        'pt' => [
            'o ',
            'a ',
            'os ',
            'as ',
            'um ',
            'uma ',
        ],
        'nl' => [
            'de ',
            'het ',
            'een ',
            'deze ',
            'dit ',
            'die ',
        ],
        'sv' => [
            'den ',
            'det ',
            'en ',
            'ett ',
            'dessa ',
            'dessa ',
        ],
        'tr' => [
            'bu ',
            'şu ',
            'o ',
            'bir ',
            'bu ',
            'şu ',
        ],
    ];

    protected function __construct(
        protected ?string $title = null,
        protected ?string $language = null,
        protected ?string $series = null,
        protected ?string $volume = null,
        protected ?string $author = null,
        protected ?string $year = null,
        protected ?string $extension = null,

        protected ?string $slug = null,
        protected ?string $slugSimple = null,
        protected ?string $seriesSlug = null,
        protected ?string $seriesSlugSimple = null,

        protected bool $useIntl = true,
    ) {
    }

    /**
     * Create a new MetaTitle instance from an Ebook.
     *
     * @param  bool  $useIntl  Use intl extension for slugify.
     */
    public static function fromEbook(Ebook $ebook, bool $useIntl = true): ?self
    {
        if (! $ebook->getTitle()) {
            return null;
        }

        $self = new self(
            title: $ebook->getTitle(),
            language: $ebook->getLanguage(),
            series: $ebook->getSeries(),
            volume: (string) $ebook->getVolume(),
            author: $ebook->getAuthorMain()?->getName(),
            year: $ebook->getPublishDate()?->format('Y'),
            extension: $ebook->getExtension(),
        );
        $self->useIntl = $useIntl;
        $self->parse();

        return $self;
    }

    /**
     * Create a new MetaTitle instance from data.
     *
     * @param  bool  $useIntl  Use intl extension for slugify.
     */
    public static function fromData(
        string $title,
        ?string $language = null,
        ?string $series = null,
        string|int|float|null $volume = null,
        ?string $author = null,
        string|int|null $year = null,
        ?string $extension = null,
        bool $useIntl = true,
    ): self {
        $self = new self(
            title: $title,
            language: $language,
            series: $series,
            volume: (string) $volume,
            author: $author,
            year: (string) $year,
            extension: $extension,
        );
        $self->useIntl = $useIntl;
        $self->parse();

        return $self;
    }

    private function parse(): static
    {
        $title = $this->generateSlug($this->title);
        $language = $this->language ? $this->generateSlug($this->language) : null;
        $series = $this->series ? $this->generateSlug($this->series) : null;
        $volume = $this->parseVolume($this->volume);
        $author = $this->author ? $this->generateSlug($this->author) : null;
        $year = $this->year ? $this->generateSlug($this->year) : null;
        $extension = $this->extension ? strtolower($this->extension) : null;

        if (! $title) {
            return $this;
        }

        $title = $this->removeDots($title);
        $language = $this->removeDots($language);
        $series = $this->removeDots($series);
        $author = $this->removeDots($author);
        $year = $this->removeDots($year);

        $titleDeterminer = $this->removeDeterminers($this->title, $this->language);
        $seriesDeterminer = $this->removeDeterminers($this->series, $this->language);

        if ($this->series) {
            $this->slug = $this->generateSlug([
                $seriesDeterminer,
                $language,
                $volume,
                $titleDeterminer,
                $author,
                $year,
                $extension,
            ]);
        } else {
            $this->slug = $this->generateSlug([
                $titleDeterminer,
                $language,
                $author,
                $year,
                $extension,
            ]);
        }
        $this->slugSimple = $this->generateSlug([$title]);

        if (! $this->series) {
            return $this;
        }

        $this->seriesSlug = $this->generateSlug([
            $seriesDeterminer,
            $language,
            $author,
            $extension,
        ]);
        $this->seriesSlugSimple = $this->generateSlug([$series]);

        return $this;
    }

    /**
     * Get slug of book title with addional metadata, like `lord-of-the-rings-01-fellowship-of-the-ring-j-r-r-tolkien-1954-epub-en`.
     *
     * - Remove determiners, here `The`
     * - Add serie title, here `Lord of the Rings`
     * - Add volume, here `1`
     * - Add author name, here `J. R. R. Tolkien`
     * - Add year, here `1954`
     * - Add extension, here `epub`
     * - Add language, here `en`
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get simple slug of book title, like `the-fellowship-of-the-ring`.
     */
    public function getSlugSimple(): string
    {
        return $this->slugSimple;
    }

    /**
     * Get slug of serie title, like `lord-of-the-rings-j-r-r-tolkien-epub-en`.
     *
     * - Remove determiners, here `The`
     * - Add author name, here `J. R. R. Tolkien`
     * - Add extension, here `epub`
     * - Add language, here `en`
     */
    public function getSeriesSlug(): ?string
    {
        return $this->seriesSlug;
    }

    /**
     * Get simple slug of serie title, like `the-lord-of-the-rings`.
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

    private function parseVolume(?string $volume): ?string
    {
        if ($volume === null) {
            return null;
        }

        if ($volume == '0') {
            return '000';
        }

        $decimals = null;

        if (str_contains($volume, '.')) {
            $explode = explode('.', $volume);
            $volume = $explode[0];
            $decimals = $explode[1];
        }

        if (str_contains($volume, ',')) {
            $explode = explode(',', $volume);
            $volume = $explode[0];
            $decimals = $explode[1];
        }

        // add `0` before volume number to get `000` format
        $volume = str_pad($volume, 3, '0', STR_PAD_LEFT);

        if ($decimals) {
            $volume .= '.'.$decimals;
        }

        return $volume;
    }

    private function removeDeterminers(?string $string, ?string $language): ?string
    {
        if (! $string) {
            return null;
        }

        $articles = MetaTitle::DETERMINERS;

        $articlesLang = $articles['en'];

        if ($language && array_key_exists($language, $articles)) {
            $articlesLang = $articles[$language];
        }

        $uppercaseArticles = array_map('ucfirst', $articlesLang);
        $lowercaseArticles = array_map('lcfirst', $articlesLang);
        $articlesLang = array_merge($uppercaseArticles, $lowercaseArticles);

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

        if (extension_loaded('intl') && $this->useIntl) {
            return $this->slugifierIntl($title, $separator, $dictionary);
        }

        return $this->slugifierNative($title, $separator);
    }

    private function slugifierIntl(?string $text, string $divider = '-', array $dictionary = ['@' => 'at']): ?string
    {
        if (! $text) {
            return null;
        }

        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $text = $transliterator->transliterate($text);

        // Convert all dashes/underscores into separator
        $flip = $divider === '-' ? '_' : '-';

        $text = preg_replace('!['.preg_quote($flip).']+!u', $divider, $text);

        // Replace dictionary words
        foreach ($dictionary as $key => $value) {
            $dictionary[$key] = $divider.$value.$divider;
        }

        $text = str_replace(array_keys($dictionary), array_values($dictionary), $text);

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $text = preg_replace('![^'.preg_quote($divider).'\pL\pN\s\.]+!u', '', strtolower($text));

        // Replace all separator characters and whitespace by a single separator
        $text = preg_replace('!['.preg_quote($divider).'\s]+!u', $divider, $text);

        return trim($text, $divider);
    }

    private function slugifierNative(?string $text, string $divider = '-'): ?string
    {
        if (! $text) {
            return null;
        }

        // remove `'` and `"` characters
        $text = str_replace(["'"], '', $text);

        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d.]+~u', $divider, $text);

        // transliterate
        $text = $this->removeAccents($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w.]+~', '', $text);

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

    private function removeDots(?string $string): ?string
    {
        if (! $string) {
            return null;
        }

        return str_replace('.', ' ', $string);
    }

    public function removeAccents(?string $string): ?string
    {
        if (! $string) {
            return null;
        }

        $string = htmlentities($string, ENT_COMPAT, 'UTF-8');
        $string = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|ring);/', '$1', $string);

        return html_entity_decode($string);
    }
}
