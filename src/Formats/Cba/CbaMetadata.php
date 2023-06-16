<?php

namespace Kiwilan\Ebook\Formats\Cba;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\ComicMeta;
use Kiwilan\Ebook\XmlReader;

class CbaMetadata extends EbookModule
{
    protected ?CbamMetadata $cbam = null;

    protected ?string $type = null;

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);

        $xml = $ebook->toXml('xml');

        if (! $xml) {
            return $self;
        }
        $metadata = XmlReader::toArray($xml);

        $root = $metadata['@root'] ?? null;
        $self->type = match ($root) {
            'ComicInfo' => 'cbam',
            'ComicBook' => 'cbml',
            default => null,
        };

        /** @var ?CbaTemplate */
        $parser = match ($self->type) {
            'cbam' => CbamMetadata::class,
            // 'cbml' => CbaCbml::class,
            default => null,
        };

        if (! $parser) {
            throw new \Exception("Unknown metadata type: {$root}");
        }

        if ($self->type === 'cbam') {
            $self->ebook->setHasMetadata(true);
            $self->cbam = CbamMetadata::make($metadata);
        }

        return $self;
    }

    public function cbam(): ?CbamMetadata
    {
        return $this->cbam;
    }

    public function toEbook(): Ebook
    {
        match ($this->type) {
            'cbam' => $this->parseCbam(),
            default => null,
        };

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $files = $this->ebook->archive()->filter('jpg');
        if (empty($files)) {
            $files = $this->ebook->archive()->filter('jpeg');
        }

        if (! empty($files)) {
            $ebook = $files[0];
            $content = $this->ebook->archive()->content($ebook);

            return EbookCover::make($ebook->path(), $content);
        }

        return null;
    }

    public function toCounts(): Ebook
    {
        return $this->ebook;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'cbam' => $this->cbam?->toArray(),
        ];
    }

    private function arrayableToBookAuthor(array $core, ?string $role = null): array
    {
        if (empty($core)) {
            return [];
        }

        $creators = [];
        foreach ($core as $item) {
            $creators[] = new BookAuthor($item, $role);
        }

        return $creators;
    }

    private function parseCbam(): Ebook
    {
        $writers = $this->arrayableToBookAuthor($this->cbam->writers(), 'writer');
        $this->ebook->setTitle($this->cbam->title());
        if (array_key_exists(0, $writers)) {
            $this->ebook->setAuthorMain($writers[0]);
        }
        $this->ebook->setAuthors([
            ...$writers,
            ...$this->arrayableToBookAuthor($this->cbam->pencillers(), 'penciller'),
            ...$this->arrayableToBookAuthor($this->cbam->inkers(), 'inker'),
            ...$this->arrayableToBookAuthor($this->cbam->colorists(), 'colorist'),
            ...$this->arrayableToBookAuthor($this->cbam->letterers(), 'letterer'),
            ...$this->arrayableToBookAuthor($this->cbam->coverArtists(), 'cover artist'),
            ...$this->arrayableToBookAuthor($this->cbam->translators(), 'translator'),
        ]);
        $this->ebook->setDescription($this->cbam->summary());
        $this->ebook->setPublisher($this->cbam->publisher());
        //$this->ebook->setIdentifiers();
        $this->ebook->setPublishDate($this->cbam->date());
        $this->ebook->setLanguage($this->cbam->language());
        $this->ebook->setTags($this->cbam->genres());
        $this->ebook->setSeries($this->cbam->series());
        $this->ebook->setVolume($this->cbam->number());
        $this->ebook->setPagesCount($this->cbam->pageCount());

        $comicMeta = new ComicMeta(
            alternateSeries: $this->cbam->alternateSeries(),
            alternateNumber: $this->cbam->alternateNumber(),
            alternateCount: $this->cbam->alternateCount(),
            count: $this->cbam->count(),
            volume: $this->cbam->volume(),
            storyArc: $this->cbam->storyArc(),
            storyArcNumber: $this->cbam->storyArcNumber(),
            seriesGroup: $this->cbam->seriesGroup(),
            imprint: $this->cbam->imprint(),
            scanInformation: $this->cbam->scanInformation(),
            notes: $this->cbam->notes(),
            communityRating: $this->cbam->communityRating(),
            isBlackAndWhite: $this->cbam->isBlackAndWhite(),
            ageRating: $this->cbam->ageRating(),
            review: $this->cbam->review(),
            web: $this->cbam->web(),
            manga: $this->cbam->manga(),
            mainCharacterOrTeam: $this->cbam->mainCharacterOrTeam(),
            format: $this->cbam->format(),
        );
        $comicMeta->setCharacters($this->cbam->characters());
        $comicMeta->setTeams($this->cbam->teams());
        $comicMeta->setLocations($this->cbam->locations());
        $comicMeta->setEditors($this->cbam->editors());

        $this->ebook->setExtras([
            'comicMeta' => $comicMeta,
        ]);

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }
}
