<?php

namespace Kiwilan\Ebook\Cba;

abstract class CbaFormat
{
    protected string $metadataType;

    protected function __construct(
        protected ?string $title = null,
        protected ?string $series = null,
        protected ?int $number = null,
        protected ?int $count = null,
        protected ?string $volume = null,
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
        protected ?string $manga = null, // Unknown, Yes, No, YesAndRightToLeft
        protected ?string $characters = null,
        protected ?string $teams = null,
        protected ?string $locations = null,
        protected ?string $scanInformation = null,
        protected ?string $storyArc = null,
        protected ?string $seriesGroup = null,
        protected ?string $ageRating = null, // Unknown, Adults Only 18+, Early Childhood, Everyone, Everyone 10+, G, Kids to Adults, M, MA15+, Mature 17+, PG, R18+, Rating Pending, Teen, X18+
        protected ?array $pages = null,
        protected ?float $communityRating = null, // min: 0, max: 5, digits: 2
        protected ?string $mainCharacterOrTeam = null,
        protected ?string $review = null
    ) {
    }
}
