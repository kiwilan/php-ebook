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

        $genres = EbookUtils::parseStringWithSeperator($audio->getGenre());
        if (! is_array($genres)) {
            $genres = null;
        } else {
            $genres = array_map('ucfirst', $genres);
        }

        $series = $audio->getRawKey('series') ?? $audio->getRawKey('mvnm');
        $series_part = $audio->getRawKey('series-part') ?? $audio->getRawKey('mvin');
        $language = $audio->getRawKey('language') ?? $audio->getRawKey('lang');
        $narrators = $audio->getComposer();

        $this->audio = [
            'authors' => EbookUtils::parseStringWithSeperator($authors),
            'title' => $audio->getAlbum() ?? $audio->getTitle(),
            'subtitle' => $this->parseTag($audio->getRawKey('subtitle'), false),
            'publisher' => $audio->getRawKey('encoded_by'),
            'publish_year' => $audio->getYear(),
            'narrators' => EbookUtils::parseStringWithSeperator($narrators),
            'description' => $this->parseTag($audio->getDescription(), false),
            'lyrics' => $this->parseTag($audio->getLyrics()),
            'comment' => $this->parseTag($audio->getComment()),
            'synopsis' => $this->parseTag($audio->getRawKey('description_long')),
            'genres' => $genres,
            'series' => $this->parseTag($series),
            'series_sequence' => $series_part !== null ? EbookUtils::parseNumber($series_part) : null,
            'language' => $this->parseTag($language),
            'isbn' => $this->parseTag($audio->getRawKey('isbn')),
            'asin' => $this->parseTag($audio->getRawKey('asin') ?? $audio->getRawKey('audible_asin')),
            'chapters' => $audio->getMetadata()?->getQuicktime()?->getChapters(),
            'date' => $audio->getCreationDate() ?? $audio->getRawKey('origyear'),
            'is_compilation' => $audio->isCompilation(),
            'encoding' => $audio->getEncoding(),
            'track_number' => $audio->getTrackNumber(),
            'disc_number' => $audio->getDiscNumber(),
            'copyright' => $this->parseTag($audio->getRawKey('copyright')),
            'stik' => $audio->getRawKey('stik'),
            'duration' => $audio->getDuration(),
            'duration_human_readable' => $audio->getDurationHuman(),
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

    private function getAudioValue(string $key): mixed
    {
        return $this->audio[$key] ?? null;
    }

    public function toEbook(): Ebook
    {
        $authors = [];
        if ($this->getAudioValue('authors')) {
            foreach ($this->getAudioValue('authors') as $author) {
                $authors[] = new BookAuthor($author, 'author');
            }
        }

        $identifiers = [];
        if ($this->getAudioValue('isbn')) {
            $identifiers[] = ['type' => 'isbn', 'value' => $this->getAudioValue('isbn')];
        }

        $date = $this->getAudioValue('date') ? new DateTime(str_replace('/', '-', $this->getAudioValue('date'))) : null;

        $this->ebook->setAuthors($authors);
        $this->ebook->setTitle($this->getAudioValue('title'));
        $this->ebook->setPublisher($this->getAudioValue('publisher'));
        $this->ebook->setDescription($this->getAudioValue('description'));
        $this->ebook->setTags($this->getAudioValue('genres'));
        $this->ebook->setSeries($this->getAudioValue('series'));
        $this->ebook->setVolume($this->getAudioValue('series_sequence'));
        $this->ebook->setLanguage($this->getAudioValue('language'));
        if ($this->getAudioValue('isbn')) {
            $this->ebook->setIdentifier(new BookIdentifier($this->getAudioValue('isbn'), 'isbn', false));
        }
        if ($this->getAudioValue('asin')) {
            $this->ebook->setIdentifier(new BookIdentifier($this->getAudioValue('asin'), 'asin', false));
        }
        if ($date instanceof DateTime) {
            $this->ebook->setPublishDate($date);
        }
        $this->ebook->setCopyright($this->getAudioValue('copyright'));

        $chapters = [];
        $quicktime = $this->ebook->getAudio()?->getMetadata()?->getQuicktime();
        if ($quicktime && $quicktime->getChapters()) {
            foreach ($quicktime->getChapters() as $chapter) {
                $chapters[] = [
                    'timestamp' => $chapter->getTimestamp(),
                    'title' => $chapter->getTitle(),
                ];
            }
        }

        $this->ebook->setExtras([
            'subtitle' => $this->getAudioValue('subtitle'),
            'publish_year' => $this->getAudioValue('publish_year'),
            'authors' => $this->getAudioValue('authors'),
            'narrators' => $this->getAudioValue('narrators'),
            'lyrics' => $this->getAudioValue('lyrics'),
            'comment' => $this->getAudioValue('comment'),
            'synopsis' => $this->getAudioValue('synopsis'),
            'chapters' => $chapters,
            'is_compilation' => $this->getAudioValue('is_compilation'),
            'encoding' => $this->getAudioValue('encoding'),
            'track_number' => $this->getAudioValue('track_number'),
            'disc_number' => $this->getAudioValue('disc_number'),
            'stik' => $this->getAudioValue('stik'),
            'duration' => $this->getAudioValue('duration'),
            'duration_human_readable' => $this->getAudioValue('duration_human_readable'),
            'audio_title' => $this->getAudioValue('audio_title'),
            'audio_artist' => $this->getAudioValue('audio_artist'),
            'audio_album' => $this->getAudioValue('audio_album'),
            'audio_album_artist' => $this->getAudioValue('audio_album_artist'),
            'audio_composer' => $this->getAudioValue('audio_composer'),
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
            'tags' => $this->ebook->getAudio()->getRaw(),
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
