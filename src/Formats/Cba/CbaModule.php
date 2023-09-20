<?php

namespace Kiwilan\Ebook\Formats\Cba;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\Cba\Parser\CbamTemplate;
use Kiwilan\Ebook\Formats\Cba\Parser\CbaTemplate;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\ComicMeta;
use Kiwilan\XmlReader\XmlReader;

class CbaModule extends EbookModule
{
    protected ?CbamTemplate $cbam = null;

    protected ?string $type = null;

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);

        $xml = $ebook->toXml('xml');

        if (! $xml) {
            return $self;
        }
        $reader = XmlReader::make($xml);

        $root = $reader->getRoot();
        $self->type = match ($root) {
            'ComicInfo' => 'cbam',
            'ComicBook' => 'cbml',
            default => null,
        };

        /** @var ?CbaTemplate */
        $parser = match ($self->type) {
            'cbam' => CbamTemplate::class,
            // 'cbml' => CbaCbml::class,
            default => null,
        };

        if (! $parser) {
            throw new \Exception("Unknown metadata type: {$root}");
        }

        if ($self->type === 'cbam') {
            $self->ebook->setHasMetadata(true);
            $self->cbam = CbamTemplate::make($reader);
        }

        return $self;
    }

    public function getCbam(): ?CbamTemplate
    {
        return $this->cbam;
    }

    public function getType(): ?string
    {
        return $this->type;
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
        if ($this->ebook->getArchive() === null) {
            return null;
        }

        $files = $this->ebook->getArchive()->filter('jpg');
        if (empty($files)) {
            $files = $this->ebook->getArchive()->filter('jpeg');
        }

        if (! empty($files)) {
            $ebook = $files[0];
            $content = $this->ebook->getArchive()->getContents($ebook);

            return EbookCover::make($ebook->getPath(), $content);
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

    private function arrayableToBookAuthor(array $core, string $role = null): array
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
        $writers = $this->arrayableToBookAuthor($this->cbam->getWriters(), 'writer');
        $this->ebook->setTitle($this->cbam->getTitle());
        if (array_key_exists(0, $writers)) {
            $this->ebook->setAuthorMain($writers[0]);
        }
        $this->ebook->setAuthors([
            ...$writers,
            ...$this->arrayableToBookAuthor($this->cbam->getPencillers(), 'penciller'),
            ...$this->arrayableToBookAuthor($this->cbam->getInkers(), 'inker'),
            ...$this->arrayableToBookAuthor($this->cbam->getColorists(), 'colorist'),
            ...$this->arrayableToBookAuthor($this->cbam->getLetterers(), 'letterer'),
            ...$this->arrayableToBookAuthor($this->cbam->getCoverArtists(), 'cover artist'),
            ...$this->arrayableToBookAuthor($this->cbam->getTranslators(), 'translator'),
        ]);
        $this->ebook->setDescription($this->cbam->getSummary());
        $this->ebook->setPublisher($this->cbam->getPublisher());
        //$this->ebook->setIdentifiers();
        $this->ebook->setPublishDate($this->cbam->getDate());
        $this->ebook->setLanguage($this->cbam->getLanguage());
        $this->ebook->setTags($this->cbam->getGenres());
        $this->ebook->setSeries($this->cbam->getSeries());
        $this->ebook->setVolume($this->cbam->getNumber());
        $this->ebook->setPagesCount($this->cbam->getPageCount());

        $comicMeta = new ComicMeta(
            alternateSeries: $this->cbam->getAlternateSeries(),
            alternateNumber: $this->cbam->getAlternateNumber(),
            alternateCount: $this->cbam->getAlternateCount(),
            count: $this->cbam->getCount(),
            volume: $this->cbam->getVolume(),
            storyArc: $this->cbam->getStoryArc(),
            storyArcNumber: $this->cbam->getStoryArcNumber(),
            seriesGroup: $this->cbam->getSeriesGroup(),
            imprint: $this->cbam->getImprint(),
            scanInformation: $this->cbam->getScanInformation(),
            notes: $this->cbam->getNotes(),
            communityRating: $this->cbam->getCommunityRating(),
            isBlackAndWhite: $this->cbam->isBlackAndWhite(),
            ageRating: $this->cbam->getAgeRating(),
            review: $this->cbam->getReview(),
            web: $this->cbam->getWeb(),
            manga: $this->cbam->getManga(),
            mainCharacterOrTeam: $this->cbam->getMainCharacterOrTeam(),
            format: $this->cbam->getFormat(),
        );
        $comicMeta->setCharacters($this->cbam->getCharacters());
        $comicMeta->setTeams($this->cbam->getTeams());
        $comicMeta->setLocations($this->cbam->getLocations());
        $comicMeta->setEditors($this->cbam->getEditors());

        $this->ebook->setExtras([
            'comicMeta' => $comicMeta,
        ]);

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }
}
