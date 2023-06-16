<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

class MobiReader
{
    /**
     * @param  string[]  $authors
     * @param  string[]  $subjects
     * @param  string[]  $isbns
     */
    protected function __construct(
        protected array $authors = [], // 100
        protected ?string $publisher = null, // 101
        protected ?string $imprint = null, // 102
        protected ?string $description = null, // 103
        protected array $isbns = [], // 104
        protected array $subjects = [], // 105
        protected ?string $publishingDate = null, // 106
        protected ?string $review = null, // 107
        protected ?string $contributor = null, // 108
        protected ?string $rights = null, // 109
        protected ?string $subjectCode = null, // 110
        protected ?string $type = null, // 111
        protected ?string $source = null, // 112
        protected ?string $asin = null, // 113
        protected ?string $version = null, // 114
        protected ?string $sample = null, // 115
        protected ?string $startReading = null, // 116
        protected ?string $adult = null, // 117
        protected ?string $retailPrice = null, // 118
        protected ?string $retailCurrency = null, // 119
        protected ?string $Kf8Boundary = null, // 121
        protected ?string $fixedLayout = null, // 122
        protected ?string $bookType = null, // 123
        protected ?string $orientationLock = null, // 124
        protected ?string $originalResolution = null, // 125
        protected ?string $zeroGutter = null, // 126
        protected ?string $zeroMargin = null, // 127
        protected ?string $metadataResourceUri = null, // 129
        protected ?string $unknown131 = null, // 131
        protected ?string $unknown132 = null, // 132
        protected ?string $dictionaryShortName = null, // 200
        protected ?string $coverOffset = null, // 201
        protected ?string $thumbOffset = null, // 202
        protected ?string $hasFakeCover = null, // 203
        protected ?string $creatorSoftware = null, // 204
        protected ?string $creatorMajorVersion = null, // 205
        protected ?string $creatorMinorVersion = null, // 206
        protected ?string $creatorBuildNumber = null, // 207
        protected ?string $watermark = null, // 208
        protected ?string $tamperProofKeys = null, // 209
        protected ?string $fontSignature = null, // 300
        protected ?string $clippingLimit = null, // 401
        protected ?string $publisherLimit = null, // 402
        protected ?string $unknown403 = null, // 403
        protected ?string $textToSpeechFlag = null, // 404
        protected ?string $unknown405 = null, // 405
        protected ?string $rentExpirationDate = null, // 406
        protected ?string $unknown407 = null, // 407
        protected ?string $unknown450 = null, // 450
        protected ?string $unknown451 = null, // 451
        protected ?string $unknown452 = null, // 452
        protected ?string $unknown453 = null, // 453
        protected ?string $cdeContentType = null, // 501
        protected ?string $lastUpdateTime = null, // 502
        protected ?string $updatedTitle = null, // 503
        protected ?string $asin504 = null, // 504
        protected ?string $language = null, // 524
        protected ?string $writingMode = null, // 525
        protected ?string $creatorBuildNumber535 = null, // 535
        protected ?string $unknown536 = null, // 536
        protected ?string $unknown542 = null, // 542
        protected ?string $inMemory = null, // 547
        protected array $extra = [],
    ) {
    }

    /**
     * @param  MobiExthRecord[]  $records
     */
    public static function make(StreamParser $stream, array $records): self
    {
        $self = new self();
        $self->setData($records);

        return $self;
    }

    /**
     * @param  MobiExthRecord[]  $records
     */
    private function setData(array $records): self
    {
        foreach ($records as $record) {
            match ($record->type()) {
                100 => $this->authors[] = $record->data(),
                101 => $this->publisher = $record->data(),
                102 => $this->imprint = $record->data(),
                103 => $this->description = $record->data(),
                104 => $this->isbns[] = $record->data(),
                105 => $this->subjects[] = $record->data(),
                106 => $this->publishingDate = $record->data(),
                107 => $this->review = $record->data(),
                108 => $this->contributor = $record->data(),
                109 => $this->rights = $record->data(),
                110 => $this->subjectCode = $record->data(),
                111 => $this->type = $record->data(),
                112 => $this->source = $record->data(),
                113 => $this->asin = $record->data(),
                114 => $this->version = $record->data(),
                115 => $this->sample = $record->data(),
                116 => $this->startReading = $record->data(),
                117 => $this->adult = $record->data(),
                118 => $this->retailPrice = $record->data(),
                119 => $this->retailCurrency = $record->data(),
                121 => $this->Kf8Boundary = $record->data(),
                122 => $this->fixedLayout = $record->data(),
                123 => $this->bookType = $record->data(),
                124 => $this->orientationLock = $record->data(),
                125 => $this->originalResolution = $record->data(),
                126 => $this->zeroGutter = $record->data(),
                127 => $this->zeroMargin = $record->data(),
                129 => $this->metadataResourceUri = $record->data(),
                131 => $this->unknown131 = $record->data(),
                132 => $this->unknown132 = $record->data(),
                200 => $this->dictionaryShortName = $record->data(),
                201 => $this->coverOffset = $record->data(),
                202 => $this->thumbOffset = $record->data(),
                203 => $this->hasFakeCover = $record->data(),
                204 => $this->creatorSoftware = $record->data(),
                205 => $this->creatorMajorVersion = $record->data(),
                206 => $this->creatorMinorVersion = $record->data(),
                207 => $this->creatorBuildNumber = $record->data(),
                208 => $this->watermark = $record->data(),
                209 => $this->tamperProofKeys = $record->data(),
                300 => $this->fontSignature = $record->data(),
                401 => $this->clippingLimit = $record->data(),
                402 => $this->publisherLimit = $record->data(),
                403 => $this->unknown403 = $record->data(),
                404 => $this->textToSpeechFlag = $record->data(),
                405 => $this->unknown405 = $record->data(),
                406 => $this->rentExpirationDate = $record->data(),
                407 => $this->unknown407 = $record->data(),
                450 => $this->unknown450 = $record->data(),
                451 => $this->unknown451 = $record->data(),
                452 => $this->unknown452 = $record->data(),
                453 => $this->unknown453 = $record->data(),
                501 => $this->cdeContentType = $record->data(),
                502 => $this->lastUpdateTime = $record->data(),
                503 => $this->updatedTitle = $record->data(),
                504 => $this->asin504 = $record->data(),
                524 => $this->language = $record->data(),
                525 => $this->writingMode = $record->data(),
                535 => $this->creatorBuildNumber535 = $record->data(),
                536 => $this->unknown536 = $record->data(),
                542 => $this->unknown542 = $record->data(),
                547 => $this->inMemory = $record->data(),
                default => $this->extra[$record->type()] = $record->data(),
            };
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function authors(): array
    {
        return $this->authors;
    }

    public function publisher(): ?string
    {
        return $this->publisher;
    }

    public function imprint(): ?string
    {
        return $this->imprint;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return string[]
     */
    public function isbns(): array
    {
        return $this->isbns;
    }

    /**
     * @return string[]
     */
    public function subjects(): array
    {
        return $this->subjects;
    }

    public function publishingDate(): ?string
    {
        return $this->publishingDate;
    }

    public function review(): ?string
    {
        return $this->review;
    }

    public function contributor(): ?string
    {
        return $this->contributor;
    }

    public function rights(): ?string
    {
        return $this->rights;
    }

    public function subjectCode(): ?string
    {
        return $this->subjectCode;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function source(): ?string
    {
        return $this->source;
    }

    public function asin(): ?string
    {
        return $this->asin;
    }

    public function version(): ?string
    {
        return $this->version;
    }

    public function sample(): ?string
    {
        return $this->sample;
    }

    public function startReading(): ?string
    {
        return $this->startReading;
    }

    public function adult(): ?string
    {
        return $this->adult;
    }

    public function retailPrice(): ?string
    {
        return $this->retailPrice;
    }

    public function retailCurrency(): ?string
    {
        return $this->retailCurrency;
    }

    public function Kf8Boundary(): ?string
    {
        return $this->Kf8Boundary;
    }

    public function fixedLayout(): ?string
    {
        return $this->fixedLayout;
    }

    public function bookType(): ?string
    {
        return $this->bookType;
    }

    public function orientationLock(): ?string
    {
        return $this->orientationLock;
    }

    public function originalResolution(): ?string
    {
        return $this->originalResolution;
    }

    public function zeroGutter(): ?string
    {
        return $this->zeroGutter;
    }

    public function zeroMargin(): ?string
    {
        return $this->zeroMargin;
    }

    public function metadataResourceUri(): ?string
    {
        return $this->metadataResourceUri;
    }

    public function unknown131(): ?string
    {
        return $this->unknown131;
    }

    public function unknown132(): ?string
    {
        return $this->unknown132;
    }

    public function dictionaryShortName(): ?string
    {
        return $this->dictionaryShortName;
    }

    public function coverOffset(): ?string
    {
        return $this->coverOffset;
    }

    public function thumbOffset(): ?string
    {
        return $this->thumbOffset;
    }

    public function hasFakeCover(): ?string
    {
        return $this->hasFakeCover;
    }

    public function creatorSoftware(): ?string
    {
        return $this->creatorSoftware;
    }

    public function creatorMajorVersion(): ?string
    {
        return $this->creatorMajorVersion;
    }

    public function creatorMinorVersion(): ?string
    {
        return $this->creatorMinorVersion;
    }

    public function creatorBuildNumber(): ?string
    {
        return $this->creatorBuildNumber;
    }

    public function watermark(): ?string
    {
        return $this->watermark;
    }

    public function tamperProofKeys(): ?string
    {
        return $this->tamperProofKeys;
    }

    public function fontSignature(): ?string
    {
        return $this->fontSignature;
    }

    public function clippingLimit(): ?string
    {
        return $this->clippingLimit;
    }

    public function publisherLimit(): ?string
    {
        return $this->publisherLimit;
    }

    public function unknown403(): ?string
    {
        return $this->unknown403;
    }

    public function textToSpeechFlag(): ?string
    {
        return $this->textToSpeechFlag;
    }

    public function unknown405(): ?string
    {
        return $this->unknown405;
    }

    public function rentExpirationDate(): ?string
    {
        return $this->rentExpirationDate;
    }

    public function unknown407(): ?string
    {
        return $this->unknown407;
    }

    public function unknown450(): ?string
    {
        return $this->unknown450;
    }

    public function unknown451(): ?string
    {
        return $this->unknown451;
    }

    public function unknown452(): ?string
    {
        return $this->unknown452;
    }

    public function unknown453(): ?string
    {
        return $this->unknown453;
    }

    public function cdeContentType(): ?string
    {
        return $this->cdeContentType;
    }

    public function lastUpdateTime(): ?string
    {
        return $this->lastUpdateTime;
    }

    public function updatedTitle(): ?string
    {
        return $this->updatedTitle;
    }

    public function asin504(): ?string
    {
        return $this->asin504;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function writingMode(): ?string
    {
        return $this->writingMode;
    }

    public function creatorBuildNumber535(): ?string
    {
        return $this->creatorBuildNumber535;
    }

    public function unknown536(): ?string
    {
        return $this->unknown536;
    }

    public function unknown542(): ?string
    {
        return $this->unknown542;
    }

    public function inMemory(): ?string
    {
        return $this->inMemory;
    }

    public function extra(): array
    {
        return $this->extra;
    }
}
