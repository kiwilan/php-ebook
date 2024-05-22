<?php

namespace Kiwilan\Ebook\Formats\Audio;

use DateTime;
use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Models\BookAuthor;
use Kiwilan\Ebook\Models\BookIdentifier;
use Kiwilan\Ebook\Utils\EbookUtils;

class AudiobookModule extends EbookModule
{
    /** @var array<string, mixed> */
    protected array $audio = [];

    public static function make(Ebook $ebook): self
    {
        AudiobookModule::checkPackage();

        $self = new self($ebook);
        $self->create();

        return $self;
    }

    public static function checkPackage(): void
    {
        if (! \Composer\InstalledVersions::isInstalled('kiwilan/php-audio')) {
            throw new \Exception('To handle audiobooks, you have to install `kiwilan/php-audio`, see https://github.com/kiwilan/php-audio');
        }
    }

    private function create(): self
    {
        $audio = $this->ebook->getAudio();

        $authors = $audio->getArtist() ?? $audio->getAlbumArtist();
        $genres = $this->parseGenres($audio->getGenre());
        $series = $audio->getTag('series') ?? $audio->getTag('mvnm');
        $series_part = $audio->getTag('series-part') ?? $audio->getTag('mvin');
        $series_part = $this->parseTag($series_part);
        $language = $audio->getTag('language') ?? $audio->getTag('lang');
        $narrators = $audio->getComposer();

        $chapters = [];
        $quicktime = $audio->toArray()['quicktime'] ?? [];
        if (array_key_exists('chapters', $quicktime)) {
            $chapters = $quicktime['chapters'];
        }

        $this->audio = [
            'authors' => $this->parseAuthors($authors),
            'title' => $audio->getAlbum() ?? $audio->getTitle(),
            'subtitle' => $this->parseTag($audio->getTag('subtitle'), false),
            'publisher' => $audio->getTag('encoded_by'),
            'publish_year' => $audio->getYear(),
            'narrators' => $this->parseAuthors($narrators),
            'description' => $this->parseTag($audio->getDescription(), false),
            'lyrics' => $this->parseTag($audio->getLyrics()),
            'comment' => $this->parseTag($audio->getComment()),
            'synopsis' => $this->parseTag($audio->getTag('description_long')),
            'genres' => $genres,
            'series' => $this->parseTag($series),
            'series_sequence' => $series_part ? EbookUtils::parseNumber($series_part) : null,
            'language' => $this->parseTag($language),
            'isbn' => $this->parseTag($audio->getTag('isbn')),
            'asin' => $this->parseTag($audio->getTag('asin') ?? $audio->getTag('audible_asin')),
            'chapters' => $chapters,
            'date' => $audio->getCreationDate() ?? $audio->getTag('origyear'),
            'is_compilation' => $audio->isCompilation(),
            'encoding' => $audio->getEncoding(),
            'track_number' => $audio->getTrackNumber(),
            'disc_number' => $audio->getDiscNumber(),
            'copyright' => $this->parseTag($audio->getTag('copyright')),
            'stik' => $audio->getStik(),
            'duration' => $audio->getDuration(),
            'audio_title' => $audio->getTitle(),
            'audio_artist' => $audio->getArtist(),
            'audio_album' => $audio->getAlbum(),
            'audio_album_artist' => $audio->getAlbumArtist(),
            'audio_composer' => $audio->getComposer(),
        ];

        return $this;
    }

    public function getAudio(): array
    {
        return $this->audio;
    }

    public function toEbook(): Ebook
    {
        $authors = [];
        foreach ($this->audio['authors'] as $author) {
            $authors[] = new BookAuthor($author, 'author');
        }

        $identifiers = [];
        if ($this->audio['isbn']) {
            $identifiers[] = ['type' => 'isbn', 'value' => $this->audio['isbn']];
        }

        $date = $this->audio['date'] ? new DateTime(str_replace('/', '-', $this->audio['date'])) : null;

        $this->ebook->setAuthors($authors);
        $this->ebook->setTitle($this->audio['title']);
        $this->ebook->setPublisher($this->audio['publisher']);
        $this->ebook->setDescription($this->audio['description']);
        $this->ebook->setDescriptionHtml("<p>{$this->audio['description']}</p>");
        $this->ebook->setTags($this->audio['genres']);
        $this->ebook->setSeries($this->audio['series']);
        $this->ebook->setVolume($this->audio['series_sequence']);
        $this->ebook->setLanguage($this->audio['language']);
        if ($this->audio['isbn']) {
            $this->ebook->setIdentifier(new BookIdentifier($this->audio['isbn'], 'isbn', false));
        }
        if ($this->audio['asin']) {
            $this->ebook->setIdentifier(new BookIdentifier($this->audio['asin'], 'asin', false));
        }
        if ($date instanceof DateTime) {
            $this->ebook->setPublishDate($date);
        }
        $this->ebook->setCopyright($this->audio['copyright']);

        $this->ebook->setExtras([
            'subtitle' => $this->audio['subtitle'],
            'publish_year' => $this->audio['publish_year'],
            'authors' => $this->audio['authors'],
            'narrators' => $this->audio['narrators'],
            'lyrics' => $this->audio['lyrics'],
            'comment' => $this->audio['comment'],
            'synopsis' => $this->audio['synopsis'],
            'chapters' => $this->audio['chapters'],
            'is_compilation' => $this->audio['is_compilation'],
            'encoding' => $this->audio['encoding'],
            'track_number' => $this->audio['track_number'],
            'disc_number' => $this->audio['disc_number'],
            'stik' => $this->audio['stik'],
            'duration' => $this->audio['duration'],
            'audio_title' => $this->audio['audio_title'],
            'audio_artist' => $this->audio['audio_artist'],
            'audio_album' => $this->audio['audio_album'],
            'audio_album_artist' => $this->audio['audio_album_artist'],
            'audio_composer' => $this->audio['audio_composer'],
        ]);

        $this->ebook->setHasParser(true);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        $audio = $this->ebook->getAudio();

        return EbookCover::make($audio->getCover()->getMimeType(), $audio->getCover()->getContents());
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
            'tags' => $this->ebook->getAudio()->getTags(),
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

    /**
     * @return string[]
     */
    private function parseGenres(?string $genres): array
    {
        if (! $genres) {
            return [];
        }

        $items = [];
        if (str_contains($genres, ';')) {
            $items = explode(';', $genres);
        } elseif (str_contains($genres, '/')) {
            $items = explode('/', $genres);
        } elseif (str_contains($genres, '//')) {
            $items = explode('//', $genres);
        } elseif (str_contains($genres, ',')) {
            $items = explode(',', $genres);
        } else {
            $items = [$genres];
        }

        $items = array_map('trim', $items);
        $items = array_map('ucfirst', $items);

        return $items;
    }

    /**
     * @return string[]
     */
    private function parseAuthors(?string $authors): array
    {
        if (! $authors) {
            return [];
        }

        $items = [];
        if (str_contains($authors, ',')) {
            $items = explode(',', $authors);
        } elseif (str_contains($authors, ';')) {
            $items = explode(';', $authors);
        } elseif (str_contains($authors, '&')) {
            $items = explode('&', $authors);
        } elseif (str_contains($authors, 'and')) {
            $items = explode('and', $authors);
        } else {
            $items = [$authors];
        }

        return array_map('trim', $items);
    }

    private function parseTag(?string $tag, bool $flat = true): ?string
    {
        if (! $tag) {
            return null;
        }

        $tag = html_entity_decode($tag);
        if ($flat) {
            $tag = preg_replace('/\s+/', ' ', $tag);
        }
        $tag = trim($tag);

        return $tag;
    }
}
