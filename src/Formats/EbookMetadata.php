<?php

namespace Kiwilan\Ebook\Formats;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;

abstract class EbookMetadata
{
    protected ?float $timeStart = null;

    protected ?float $timeEnd = null;

    /**
     * @var array<string, mixed>
     */
    protected array $extras = [];

    protected function __construct(
        protected Ebook $ebook,
    ) {
    }

    abstract public static function make(Ebook $ebook): self;

    abstract public function toEbook(): Ebook;

    abstract public function toCover(): ?EbookCover;

    abstract public function toCounts(): Ebook;

    public function timeStart(): ?float
    {
        return $this->timeStart;
    }

    public function timeEnd(): ?float
    {
        return $this->timeEnd;
    }

    public function getExecTime(): float
    {
        $execTime = $this->timeEnd - $this->timeStart;

        return number_format((float) $execTime, 5, '.', '');
    }

    public function extras(): array
    {
        return $this->extras;
    }

    public function setStartTime(float $time): self
    {
        $this->timeStart = $time;

        return $this;
    }

    public function setEndTime(float $time): self
    {
        $this->timeEnd = $time;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $extras
     */
    public function setExtras(array $extras): self
    {
        $this->extras = $extras;

        return $this;
    }

    abstract public function toArray(): array;

    abstract public function toJson(): string;

    abstract public function __toString(): string;
}
