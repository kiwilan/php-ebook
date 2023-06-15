<?php

namespace Kiwilan\Ebook\Formats\Mobi;

class MobiHeadMeta
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
        protected ?string $startreading = null, // 116
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
        protected ?string $MetadataResourceUri = null, // 129
        protected ?string $unknown131 = null, // 131
        protected ?string $unknown132 = null, // 132
        protected ?string $dictionaryShortName = null, // 200
        protected ?string $coveroffset = null, // 201
        protected ?string $thumboffset = null, // 202
        protected ?string $hasfakecover = null, // 203
        protected ?string $creatorsoftware = null, // 204
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
    ) {
    }

    /**
     * @param  MobiExthRecord[]  $records
     */
    public static function make(array $records): self
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
            $type = $record->type();
            $data = $record->data();

            if ($type === 100) {
                $this->authors[] = $data;
            }

            if ($type === 101) {
                $this->publisher = $data;
            }

            if ($type === 102) {
                $this->imprint = $data;
            }

            if ($type === 103) {
                $this->description = $data;
            }

            if ($type === 104) {
                $this->isbns[] = $data;
            }

            if ($type === 105) {
                $this->subjects[] = $data;
            }

            if ($type === 106) {
                $this->publishingDate = $data;
            }

            if ($type === 107) {
                $this->review = $data;
            }

            if ($type === 108) {
                $this->contributor = $data;
            }

            if ($type === 109) {
                $this->rights = $data;
            }

            if ($type === 110) {
                $this->subjectCode = $data;
            }

            if ($type === 111) {
                $this->type = $data;
            }

            if ($type === 112) {
                $this->source = $data;
            }

            if ($type === 113) {
                $this->asin = $data;
            }

            if ($type === 114) {
                $this->version = $data;
            }

            if ($type === 115) {
                $this->sample = $data;
            }

            if ($type === 116) {
                $this->startreading = $data;
            }

            if ($type === 117) {
                $this->adult = $data;
            }

            if ($type === 118) {
                $this->retailPrice = $data;
            }

            if ($type === 119) {
                $this->retailCurrency = $data;
            }

            if ($type === 121) {
                $this->Kf8Boundary = $data;
            }

            if ($type === 122) {
                $this->fixedLayout = $data;
            }

            if ($type === 123) {
                $this->bookType = $data;
            }

            if ($type === 124) {
                $this->orientationLock = $data;
            }

            if ($type === 125) {
                $this->originalResolution = $data;
            }

            if ($type === 126) {
                $this->zeroGutter = $data;
            }

            if ($type === 127) {
                $this->zeroMargin = $data;
            }

            if ($type === 129) {
                $this->MetadataResourceUri = $data;
            }

            if ($type === 131) {
                $this->unknown131 = $data;
            }

            if ($type === 132) {
                $this->unknown132 = $data;
            }

            if ($type === 200) {
                $this->dictionaryShortName = $data;
            }

            if ($type === 201) {
                $this->coveroffset = $data;
            }

            if ($type === 202) {
                $this->thumboffset = $data;
            }

            if ($type === 203) {
                $this->hasfakecover = $data;
            }

            if ($type === 204) {
                $this->creatorsoftware = $data;
            }

            if ($type === 205) {
                $this->creatorMajorVersion = $data;
            }

            if ($type === 206) {
                $this->creatorMinorVersion = $data;
            }

            if ($type === 207) {
                $this->creatorBuildNumber = $data;
            }

            if ($type === 208) {
                $this->watermark = $data;
            }

            if ($type === 209) {
                $this->tamperProofKeys = $data;
            }

            if ($type === 300) {
                $this->fontSignature = $data;
            }

            if ($type === 401) {
                $this->clippingLimit = $data;
            }

            if ($type === 402) {
                $this->publisherLimit = $data;
            }

            if ($type === 403) {
                $this->unknown403 = $data;
            }

            if ($type === 404) {
                $this->textToSpeechFlag = $data;
            }

            if ($type === 405) {
                $this->unknown405 = $data;
            }

            if ($type === 406) {
                $this->rentExpirationDate = $data;
            }

            if ($type === 407) {
                $this->unknown407 = $data;
            }

            if ($type === 450) {
                $this->unknown450 = $data;
            }

            if ($type === 451) {
                $this->unknown451 = $data;
            }

            if ($type === 452) {
                $this->unknown452 = $data;
            }

            if ($type === 453) {
                $this->unknown453 = $data;
            }

            if ($type === 501) {
                $this->cdeContentType = $data;
            }

            if ($type === 502) {
                $this->lastUpdateTime = $data;
            }

            if ($type === 503) {
                $this->updatedTitle = $data;
            }

            if ($type === 504) {
                $this->asin504 = $data;
            }

            if ($type === 524) {
                $this->language = $data;
            }

            if ($type === 525) {
                $this->writingMode = $data;
            }

            if ($type === 535) {
                $this->creatorBuildNumber535 = $data;
            }

            if ($type === 536) {
                $this->unknown536 = $data;
            }

            if ($type === 542) {
                $this->unknown542 = $data;
            }

            if ($type === 547) {
                $this->inMemory = $data;
            }

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

    public function startreading(): ?string
    {
        return $this->startreading;
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

    public function MetadataResourceUri(): ?string
    {
        return $this->MetadataResourceUri;
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

    public function coveroffset(): ?string
    {
        return $this->coveroffset;
    }

    public function thumboffset(): ?string
    {
        return $this->thumboffset;
    }

    public function hasfakecover(): ?string
    {
        return $this->hasfakecover;
    }

    public function creatorsoftware(): ?string
    {
        return $this->creatorsoftware;
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
}
