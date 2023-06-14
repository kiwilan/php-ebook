<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;

class EpubMetadata extends EbookModule
{
    protected ?EpubContainer $container = null;

    protected ?OpfMetadata $opf = null;

    protected ?NcxMetadata $ncx = null;

    protected ?string $coverPath = null;

    /** @var string[] */
    protected array $files = [];

    protected ?int $pagesCount = null;

    protected ?int $wordsCount = null;

    /** @var EpubHtml[] */
    protected array $html = [];

    /** @var EpubChapter[] */
    protected array $chapters = [];

    protected function __construct(
    ) {
        parent::__construct(...func_get_args());
    }

    public static function make(Ebook $ebook): self
    {
        $self = new self($ebook);
        $self->create();

        return $self;
    }

    private function create(): self
    {
        $xml = $this->ebook->toXml('container.xml');

        if (! $xml) {
            return $this;
        }

        $this->container = EpubContainer::make($xml);

        $xml = $this->ebook->toXml($this->container->opfPath());
        if (! $xml) {
            return $this;
        }

        $this->ebook->setHasMetadata(true);
        $this->opf = OpfMetadata::make($xml, $this->ebook->filename());
        $this->coverPath = $this->opf->coverPath();
        $this->files = $this->opf->contentFiles();

        return $this;
    }

    public function toEbook(): Ebook
    {
        $altTitle = explode('.', $this->ebook->filename());
        $altTitle = $altTitle[0] ?? 'untitled';

        $this->ebook->setTitle($this->opf->dcTitle() ?? $altTitle);

        $authors = array_values($this->opf->dcCreators());
        $this->ebook->setAuthors($authors);
        if ($this->opf->dcDescription()) {
            $this->ebook->setDescription(strip_tags($this->opf->dcDescription()));
        }
        $this->ebook->setCopyright(! empty($this->opf->dcRights()) ? implode(', ', $this->opf->dcRights()) : null);
        $this->ebook->setPublisher($this->opf->dcPublisher());
        $this->ebook->setIdentifiers($this->opf->dcIdentifiers());
        $this->ebook->setPublishDate($this->opf->dcDate());
        $this->ebook->setLanguage($this->opf->dcLanguage());

        $tags = [];
        if (! empty($this->opf->dcSubject())) {
            foreach ($this->opf->dcSubject() as $subject) {
                if (strlen($subject) < 50) {
                    $tags[] = $subject;
                }
            }
        }
        $this->ebook->setTags($tags);

        $rating = null;
        if (! empty($this->opf->meta())) {
            foreach ($this->opf->meta() as $meta) {
                if ($meta->name() === 'calibre:series') {
                    $this->ebook->setSeries($meta->content());
                }
                if ($meta->name() === 'calibre:series_index') {
                    $this->ebook->setVolume((int) $meta->content());
                }
                if ($meta->name() === 'calibre:rating') {
                    $rating = (float) $meta->content();
                }
            }
        }

        $contributor = ! empty($this->opf->dcContributors()) ? implode(', ', $this->opf->dcContributors()) : null;
        $this->ebook->setExtras([
            'contributor' => $contributor,
            'rating' => $rating,
        ]);

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        if (! $this->coverPath) {
            return null;
        }

        $file = $this->ebook->archive()->find($this->coverPath);
        $content = $this->ebook->archive()->content($file);

        return EbookCover::make($this->coverPath, $content);
    }

    public function toCounts(): Ebook
    {
        if (! $this->wordsCount || ! $this->pagesCount) {
            $this->setCounts();
        }

        $this->ebook->setWordsCount($this->wordsCount);
        $this->ebook->setPagesCount($this->pagesCount);

        return $this->ebook;
    }

    private function setCounts(): array
    {
        if (empty($this->html)) {
            $this->parseFiles();
        }

        $wordsCount = 0;
        foreach ($this->html as $html) {
            $body = $html->body();
            $content = strip_tags($body);
            $content = preg_replace('/[\r\n|\n|\r)]+/', '', $content);
            $words = str_word_count($content, 1);

            $wordsCount += count($words);
        }

        $pagesCount = (int) ceil($wordsCount / Ebook::wordsByPage());

        $this->wordsCount = $wordsCount;
        $this->pagesCount = $pagesCount;

        return [
            'words' => $wordsCount,
            'pages' => $pagesCount,
        ];
    }

    private function parseFiles(): array
    {
        $items = [];
        foreach ($this->files as $path) {
            $file = $this->ebook->archive()->find($path);
            if (! $file) {
                continue;
            }
            $html = $this->ebook->archive()->content($file);
            $items[] = EpubHtml::make($html, $file->filename());
        }

        $this->html = $items;

        return $items;
    }

    private function parseChapters(): array
    {
        if (empty($this->html)) {
            $this->parseFiles();
        }

        if (empty($this->ncx)) {
            $this->ncx = $this->parseNcx();
        }

        $chapters = EpubChapter::toArray($this->ncx, $this->html);
        $this->chapters = $chapters;

        return $chapters;
    }

    private function parseNcx(): ?NcxMetadata
    {
        $manifest = $this->opf->manifest();
        $items = reset($manifest);

        $path = null;
        foreach ($items as $item) {
            if (array_key_exists('@attributes', $item)) {
                $attributes = $item['@attributes'];
                $href = $attributes['href'] ?? null;

                if (str_contains($href, 'ncx')) {
                    $path = $href;
                }
            }
        }

        if (! $path) {
            return null;
        }

        $item = $this->ebook->archive()->find($path);
        $xml = $this->ebook->archive()->content($item);

        $ncx = NcxMetadata::make($xml);

        return $ncx;
    }

    public function container(): ?EpubContainer
    {
        return $this->container;
    }

    public function opf(): ?OpfMetadata
    {
        return $this->opf;
    }

    public function ncx(): ?NcxMetadata
    {
        if (is_null($this->ncx)) {
            $this->ncx = $this->parseNcx();
        }

        return $this->ncx;
    }

    public function coverPath(): ?string
    {
        return $this->coverPath;
    }

    public function pagesCount(): ?int
    {
        if (is_null($this->pagesCount)) {
            $this->setCounts();
        }

        return $this->pagesCount;
    }

    public function wordsCount(): ?int
    {
        if (is_null($this->wordsCount)) {
            $this->setCounts();
        }

        return $this->wordsCount;
    }

    /**
     * @return EpubChapter[]
     */
    public function chapters(): array
    {
        if (empty($this->chapters)) {
            $this->parseChapters();
        }

        return $this->chapters;
    }

    /**
     * @return EpubHtml[]
     */
    public function html(): array
    {
        if (empty($this->html)) {
            $this->parseFiles();
        }

        return $this->html;
    }

    /**
     * @return string[]
     */
    public function files(): array
    {
        return $this->files;
    }

    public function toArray(): array
    {
        return [
            'container' => $this->container?->toArray(),
            'opf' => $this->opf?->toArray(),
            'ncx' => $this->ncx?->toArray(),
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
