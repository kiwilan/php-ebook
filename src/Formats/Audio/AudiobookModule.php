<?php

namespace Kiwilan\Ebook\Formats\Audio;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Tools\BookAuthor;

class AudiobookModule extends EbookModule
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
        $audio = $this->ebook->getAudio();

        $this->audio = [
            'title' => $audio->getTitle(),
            'artist' => $audio->getArtist(),
            'albumArtist' => $audio->getAlbumArtist(),
            'album' => $audio->getAlbum(),
            'genre' => $audio->getGenre(),
            'year' => $audio->getYear(),
            'trackNumber' => $audio->getTrackNumber(),
            'description' => $audio->getDescription(),
            'comment' => $audio->getComment(),
            'creationDate' => $audio->getCreationDate(),
            'composer' => $audio->getComposer(),
            'discNumber' => $audio->getDiscNumber(),
            'isCompilation' => $audio->isCompilation(),
            'encoding' => $audio->getEncoding(),
            'lyrics' => $audio->getLyrics(),
            'stik' => $audio->getStik(),
            'duration' => $audio->getDuration(),
        ];

        return $this;
    }

    public function getAudio(): array
    {
        return $this->audio;
    }

    public function toEbook(): Ebook
    {
        $audio = $this->ebook->getAudio();

        $author = new BookAuthor($audio->getArtist());

        $date = null;
        if ($audio->getCreationDate()) {
            $date = new DateTime($audio->getCreationDate());
        } elseif ($audio->getYear()) {
            $date = new DateTime("{$audio->getYear()}-01-01");
        }

        $description = "{$audio->getDescription()} {$audio->getComment()}";
        $description = trim($description);

        $this->ebook->setTitle($audio->getTitle());
        $this->ebook->setAuthors([$author]);
        $this->ebook->setPublisher($audio->getAlbumArtist());
        $this->ebook->setDescription($description);
        $this->ebook->setTags([$audio->getGenre()]);
        // $this->ebook->setLanguage($audio->getLanguage());
        $this->ebook->setSeries($audio->getAlbum());
        $this->ebook->setVolume($audio->getTrackNumber());
        $this->ebook->setPublishDate($date);
        $this->ebook->setCopyright($audio->getEncodingBy());
        $this->ebook->setExtras([
            'composer' => $audio->getComposer(),
            'discNumber' => $audio->getDiscNumber(),
            'isCompilation' => $audio->isCompilation(),
            'encoding' => $audio->getEncoding(),
            'lyrics' => $audio->getLyrics(),
            'stik' => $audio->getStik(),
            'duration' => $audio->getDuration(),
        ]);

        $this->ebook->setHasMetadata(true);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $audio = $this->ebook->getAudio();

        return EbookCover::make($audio->getCover()->getMimeType(), $audio->getCover()->getContent());
    }

    public function toCounts(): Ebook
    {
        $audio = $this->ebook->getAudio();

        $this->ebook->setPagesCount(intval($audio->getDuration()));

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
