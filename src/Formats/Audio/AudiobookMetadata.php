<?php

namespace Kiwilan\Ebook\Formats\Audio;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;

class AudiobookMetadata extends EbookModule
{
    /** @var array<string, mixed> */
    protected array $audio = [];

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);
        $self->create();

        return $self;
    }

    private function create(): self
    {
        $audio = $this->ebook->audio();

        $this->audio = [
            'title' => $audio->title(),
            'artist' => $audio->artist(),
            'albumArtist' => $audio->albumArtist(),
            'album' => $audio->album(),
            'genre' => $audio->genre(),
            'year' => $audio->year(),
            'trackNumber' => $audio->trackNumber(),
            'description' => $audio->description(),
            'comment' => $audio->comment(),
            'creationDate' => $audio->creationDate(),
            'composer' => $audio->composer(),
            'discNumber' => $audio->discNumber(),
            'isCompilation' => $audio->isCompilation(),
            'encoding' => $audio->encoding(),
            'lyrics' => $audio->lyrics(),
            'stik' => $audio->stik(),
            'duration' => $audio->duration(),
        ];

        return $this;
    }

    public function audio(): array
    {
        return $this->audio;
    }

    public function toEbook(): Ebook
    {
        $audio = $this->ebook->audio();

        $author = new BookAuthor($audio->artist());

        $date = null;
        if ($audio->creationDate()) {
            $date = new DateTime($audio->creationDate());
        } elseif ($audio->year()) {
            $date = new DateTime("{$audio->year()}-01-01");
        }

        $description = "{$audio->description()} {$audio->comment()}";
        $description = trim($description);

        $this->ebook->setTitle($audio->title());
        $this->ebook->setAuthors([$author]);
        $this->ebook->setPublisher($audio->albumArtist());
        $this->ebook->setDescription($description);
        $this->ebook->setTags([$audio->genre()]);
        // $this->ebook->setLanguage($audio->language());
        $this->ebook->setSeries($audio->album());
        $this->ebook->setVolume($audio->trackNumber());
        $this->ebook->setPublishDate($date);
        $this->ebook->setCopyright($audio->encodingBy());
        $this->ebook->setExtras([
            'composer' => $audio->composer(),
            'discNumber' => $audio->discNumber(),
            'isCompilation' => $audio->isCompilation(),
            'encoding' => $audio->encoding(),
            'lyrics' => $audio->lyrics(),
            'stik' => $audio->stik(),
            'duration' => $audio->duration(),
        ]);

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $audio = $this->ebook->audio();

        return EbookCover::make($audio->cover()->mimeType(), $audio->cover()->content());
    }

    public function toCounts(): Ebook
    {
        $audio = $this->ebook->audio();

        $this->ebook->setPagesCount(intval($audio->duration()));

        return $this->ebook;
    }

    public function toArray(): array
    {
        return [
            'audio' => $this->audio,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
