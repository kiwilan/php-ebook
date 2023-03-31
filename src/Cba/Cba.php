<?php

namespace Kiwilan\Ebook\Cba;

use DateTime;
use Kiwilan\Ebook\BookEntity;

abstract class Cba
{
    // protected string $metadataFilename = 'ComicInfo.xml';

    // protected ?array $pages = null;

    /** @var string[] */
    protected array $writers = [];

    /** @var string[] */
    protected array $pencillers = [];

    /** @var string[] */
    protected array $inkers = [];

    /** @var string[] */
    protected array $colorists = [];

    /** @var string[] */
    protected array $letterers = [];

    /** @var string[] */
    protected array $coverArtists = [];

    /** @var string[] */
    protected array $editors = [];

    /** @var string[] */
    protected array $publishers = [];

    /** @var string[] */
    protected array $imprints = [];

    /** @var string[] */
    protected array $genres = [];

    /** @var string[] */
    protected array $characters = [];

    /** @var string[] */
    protected array $teams = [];

    /** @var string[] */
    protected array $locations = [];

    /** @var string[] */
    protected array $extras = [];

    protected function __construct(
        protected ?string $title = null,
        protected ?string $series = null,
        protected ?int $number = null,
        protected ?int $count = null,
        protected ?int $volume = null,
        protected ?string $summary = null,
        protected ?string $notes = null,
        protected ?DateTime $date = null,
        protected ?string $web = null,
        protected ?int $pageCount = null,
        protected ?string $language = null,
        protected ?string $format = null,
        protected bool $isBlackAndWhite = false,
        protected CbamMangaEnum $manga = CbamMangaEnum::NO,
        protected ?string $scanInformation = null,
        protected ?string $storyArc = null,
        protected ?string $seriesGroup = null,
        protected CbamAgeRatingEnum $ageRating = CbamAgeRatingEnum::UNKNOWN,
        protected ?float $communityRating = null, // min: 0, max: 5, digits: 2
        protected ?string $mainCharacterOrTeam = null,
        protected ?string $review = null
    ) {
    }

    abstract public static function create(array $metadata): self;

    abstract public function toBook(string $path): BookEntity;
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
