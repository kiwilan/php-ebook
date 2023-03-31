<?php

namespace Kiwilan\Ebook\Cba;

use DateTime;
use Kiwilan\Ebook\BookEntity;

/**
 * @docs https://anansi-project.github.io/docs/comicinfo/schemas/v2.0
 */
class CbaCbam extends Cba
{
    protected string $metadataFilename = 'ComicInfo.xml';

    protected ?array $pages = null;

    protected function __construct(
        protected array $metadata,
        protected ?string $title = null,
        protected ?string $series = null,
        protected ?string $number = null,
        protected ?int $count = null,
        protected ?int $volume = null,
        protected ?string $alternateSeries = null,
        protected ?string $alternateNumber = null,
        protected ?string $alternateCount = null,
        protected ?string $summary = null,
        protected ?string $notes = null,
        protected ?int $year = null,
        protected ?int $month = null,
        protected ?int $day = null,
        protected ?string $writer = null,
        protected ?string $penciller = null,
        protected ?string $inker = null,
        protected ?string $colorist = null,
        protected ?string $letterer = null,
        protected ?string $coverArtist = null,
        protected ?string $editor = null,
        protected ?string $publisher = null,
        protected ?string $imprint = null,
        protected ?string $genre = null,
        protected ?string $web = null,
        protected ?int $pageCount = null,
        protected ?string $languageIso = null,
        protected ?string $format = null,
        protected ?string $blackAndWhite = null, // Yes / No
        protected ?CbamMangaEnum $manga = null, // Unknown, Yes, No, YesAndRightToLeft
        protected ?string $characters = null,
        protected ?string $teams = null,
        protected ?string $locations = null,
        protected ?string $scanInformation = null,
        protected ?string $storyArc = null,
        protected ?string $seriesGroup = null,
        protected ?CbamAgeRatingEnum $ageRating = null, // Unknown, Adults Only 18+, Early Childhood, Everyone, Everyone 10+, G, Kids to Adults, M, MA15+, Mature 17+, PG, R18+, Rating Pending, Teen, X18+
        protected ?float $communityRating = null, // min: 0, max: 5, digits: 2
        protected ?string $mainCharacterOrTeam = null,
        protected ?string $review = null
    ) {
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public static function create(array $metadata): static
    {
        $self = new self($metadata);
        $self->parse();

        return $self;
    }

    public function toBook(string $path): BookEntity
    {
        $date = null;
        $year = $this->year ?? null;
        $month = $this->month ?? '01';
        $day = $this->day ?? '01';

        if ($year) {
            $date = "{$year}-{$month}-{$day}";
            $date = new DateTime($date);
        }

        $book = new BookEntity($path);
        // $book = new CbaEntity(
        //     title: $this->title,
        //     series: $this->series,
        //     number: $this->number,
        //     count: $this->count,
        //     volume: $this->volume,
        //     summary: $this->summary,
        //     notes: $this->notes,
        //     date: $date,
        //     web: $this->web,
        //     pageCount: $this->pageCount,
        //     language: $this->languageIso,
        //     format: $this->format,
        //     isBlackAndWhite: $this->blackAndWhite === 'Yes',
        //     manga: $this->manga ?? CbamMangaEnum::UNKNOWN,
        //     scanInformation: $this->scanInformation,
        //     storyArc: $this->storyArc,
        //     seriesGroup: $this->seriesGroup,
        //     ageRating: $this->ageRating ?? CbamAgeRatingEnum::UNKNOWN,
        //     communityRating: $this->communityRating,
        //     mainCharacterOrTeam: $this->mainCharacterOrTeam,
        //     review: $this->review,
        // );

        // $book->setColorists($this->valueToArray($this->colorist));
        // $book->setCoverArtists($this->valueToArray($this->coverArtist));
        // $book->setEditors($this->valueToArray($this->editor));
        // $book->setImprints($this->valueToArray($this->imprint));
        // $book->setInkers($this->valueToArray($this->inker));
        // $book->setLetterers($this->valueToArray($this->letterer));
        // $book->setPencillers($this->valueToArray($this->penciller));
        // $book->setPublishers($this->valueToArray($this->publisher));
        // $book->setWriters($this->valueToArray($this->writer));
        // $book->setGenres($this->valueToArray($this->genre));
        // $book->setCharacters($this->valueToArray($this->characters));
        // $book->setTeams($this->valueToArray($this->teams));
        // $book->setLocations($this->valueToArray($this->locations));

        // $authors = BookCreator::createFromArray($cba->authors());

        // $this->book()->setTitle($cba->title());
        // $this->book()->setSeries($cba->series());
        // $this->book()->setVolume($cba->number());
        // $this->book()->setAuthors($authors);
        // $this->book()->setPublisher($cba->publisher());
        // $this->book()->setLanguage($cba->languageIso());

        return $book;
    }

    private function valueToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        $value = trim($value);
        $value = str_replace(';', ',', $value);
        $value = explode(',', $value);

        return array_map(fn ($v) => trim($v), $value);
    }

    private function parse(): void
    {
        $this->title = $this->extract('Title');
        $this->series = $this->extract('Series');
        $this->number = $this->extract('Number');
        $this->count = $this->extractInt('Count');
        $this->volume = $this->extractInt('Volume');
        $this->alternateSeries = $this->extract('AlternateSeries');
        $this->alternateNumber = $this->extract('AlternateNumber');
        $this->alternateCount = $this->extract('AlternateCount');
        $this->summary = $this->extract('Summary');
        $this->notes = $this->extract('Notes');
        $this->year = $this->extractInt('Year');
        $this->month = $this->extractInt('Month');
        $this->day = $this->extractInt('Day');
        $this->writer = $this->extract('Writer');
        $this->penciller = $this->extract('Penciller');
        $this->inker = $this->extract('Inker');
        $this->colorist = $this->extract('Colorist');
        $this->letterer = $this->extract('Letterer');
        $this->coverArtist = $this->extract('CoverArtist');
        $this->editor = $this->extract('Editor');
        $this->publisher = $this->extract('Publisher');
        $this->imprint = $this->extract('Imprint');
        $this->genre = $this->extract('Genre');
        $this->web = $this->extract('Web');
        $this->pageCount = $this->extractInt('PageCount');
        $this->languageIso = $this->extract('LanguageISO');
        $this->format = $this->extract('Format');
        $this->blackAndWhite = $this->extract('BlackAndWhite');

        $manga = $this->extract('Manga');
        $this->manga = $manga ? CbamMangaEnum::tryFrom($manga) : null;

        $this->characters = $this->extract('Characters');
        $this->teams = $this->extract('Teams');
        $this->locations = $this->extract('Locations');
        $this->scanInformation = $this->extract('ScanInformation');
        $this->storyArc = $this->extract('StoryArc');
        $this->seriesGroup = $this->extract('SeriesGroup');

        $ageRating = $this->extract('AgeRating');
        $this->ageRating = $ageRating ? CbamAgeRatingEnum::tryFrom($ageRating) : null;

        $communityRating = $this->extract('CommunityRating');
        $this->communityRating = $communityRating ? (float) $communityRating : null;

        $this->mainCharacterOrTeam = $this->extract('MainCharacterOrTeam');
        $this->review = $this->extract('Review');

        $pages = $this->metadata['Pages'] ?? null;

        if ($pages && array_key_exists('Page', $pages)) {
            $pages = $pages['Page'];

            $items = [];
            foreach ($pages as $page) {
                if (array_key_exists('@attributes', $page)) {
                    $items[] = $page['@attributes'];
                }
            }

            $this->pages = $items;
        }
    }

    private function extract(string $key): ?string
    {
        $string = $this->metadata[$key] ?? null;

        if (! $string) {
            return null;
        }

        return $this->normalizeString($string);
    }

    private function extractInt(string $key): ?int
    {
        if ($this->extract($key)) {
            return (int) $this->extract($key);
        }

        return null;
    }

    private function normalizeString(string $string): ?string
    {
        if (empty($string)) {
            return null;
        }

        $string = preg_replace('/\s+/', ' ', $string);
        $string = preg_replace("/\r|\n/", '', $string);
        $string = trim($string);

        return $string;
    }
}

enum CbamAgeRatingEnum: string
{
    case UNKNOWN = 'Unknown';

    case ADULTS_ONLY_18_PLUS = 'Adults Only 18+';

    case EARLY_CHILDHOOD = 'Early Childhood';

    case EVERYONE = 'Everyone';

    case EVERYONE_10_PLUS = 'Everyone 10+';

    case G = 'G';

    case KIDS_TO_ADULTS = 'Kids to Adults';

    case M = 'M';

    case MA15_PLUS = 'MA15+';

    case MATURE_17_PLUS = 'Mature 17+';

    case PG = 'PG';

    case R18_PLUS = 'R18+';

    case RATING_PENDING = 'Rating Pending';

    case TEEN = 'Teen';

    case X18_PLUS = 'X18+';
}

enum CbamMangaEnum: string
{
    case UNKNOWN = 'Unknown';

    case YES = 'Yes';

    case NO = 'No';

    case YES_AND_RIGHT_TO_LEFT = 'YesAndRightToLeft';
}
