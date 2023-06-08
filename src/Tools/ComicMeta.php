<?php

namespace Kiwilan\Ebook\Tools;

class ComicMeta
{
    /** @var string[] */
    protected ?array $characters = null;

    /** @var string[] */
    protected ?array $teams = null;

    /** @var string[] */
    protected ?array $locations = null;

    public function __construct(
        protected ?string $alternateSeries = null,
        protected ?int $alternateNumber = null,
        protected ?string $alternateCount = null,
        protected ?int $count = null,
        protected ?int $volume = null,
        protected ?string $storyArc = null,
        protected ?int $storyArcNumber = null,
        protected ?string $seriesGroup = null,
        protected ?string $imprint = null,
    ) {
    }

    /**
     * @return string[]
     */
    public function characters(): array
    {
        return $this->characters;
    }

    /**
     * @return string[]
     */
    public function teams(): array
    {
        return $this->teams;
    }

    /**
     * @return string[]
     */
    public function locations(): array
    {
        return $this->locations;
    }

    public function alternateSeries(): ?string
    {
        return $this->alternateSeries;
    }

    public function alternateNumber(): ?int
    {
        return $this->alternateNumber;
    }

    public function alternateCount(): ?string
    {
        return $this->alternateCount;
    }

    public function count(): ?int
    {
        return $this->count;
    }

    public function volume(): ?int
    {
        return $this->volume;
    }

    public function storyArc(): ?string
    {
        return $this->storyArc;
    }

    public function storyArcNumber(): ?int
    {
        return $this->storyArcNumber;
    }

    public function seriesGroup(): ?string
    {
        return $this->seriesGroup;
    }

    public function imprint(): ?string
    {
        return $this->imprint;
    }

    /**
     * @param  string[]  $characters
     */
    public function setCharacters(array $characters): self
    {
        $this->characters = $characters;

        return $this;
    }

    /**
     * @param  string[]  $teams
     */
    public function setTeams(array $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @param  string[]  $locations
     */
    public function setLocations(array $locations): self
    {
        $this->locations = $locations;

        return $this;
    }

    public function setAlternateSeries(?string $alternateSeries): self
    {
        $this->alternateSeries = $alternateSeries;

        return $this;
    }

    public function setAlternateNumber(?int $alternateNumber): self
    {
        $this->alternateNumber = $alternateNumber;

        return $this;
    }

    public function setAlternateCount(?string $alternateCount): self
    {
        $this->alternateCount = $alternateCount;

        return $this;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function setStoryArc(?string $storyArc): self
    {
        $this->storyArc = $storyArc;

        return $this;
    }

    public function setStoryArcNumber(?int $storyArcNumber): self
    {
        $this->storyArcNumber = $storyArcNumber;

        return $this;
    }

    public function setSeriesGroup(?string $seriesGroup): self
    {
        $this->seriesGroup = $seriesGroup;

        return $this;
    }

    public function setImprint(?string $imprint): self
    {
        $this->imprint = $imprint;

        return $this;
    }
}
