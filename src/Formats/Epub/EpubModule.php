<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\EbookModule;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubChapter;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubHtml;
use Kiwilan\Ebook\Formats\Epub\Parser\NcxItem;
use Kiwilan\Ebook\Formats\Epub\Parser\OpfItem;

class EpubModule extends EbookModule
{
    protected ?EpubContainer $container = null;

    protected ?OpfItem $opf = null;

    protected ?NcxItem $ncx = null;

    protected ?string $coverPath = null;

    /** @var string[] */
    protected array $files = [];

    protected ?int $pagesCount = null;

    protected ?int $wordsCount = null;

    /** @var EpubHtml[] */
    protected array $html = [];

    /** @var EpubChapter[] */
    protected array $chapters = [];

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

        $xml = $this->ebook->toXml($this->container->getOpfPath());
        if (! $xml) {
            return $this;
        }

        $this->ebook->setHasMetadata(true);
        $this->opf = OpfItem::make($xml, $this->ebook->getFilename());
        $this->coverPath = $this->opf->getCoverPath();
        $this->files = $this->opf->getContentFiles();

        return $this;
    }

    public function toEbook(): Ebook
    {
        $altTitle = explode('.', $this->ebook->getFilename());
        $altTitle = $altTitle[0] ?? 'untitled';

        if (! $this->opf) {
            $this->ebook->setTitle($altTitle);

            return $this->ebook;
        }

        $this->ebook->setTitle($this->opf->getDcTitle() ?? $altTitle);

        $authors = array_values($this->opf->getDcCreators());
        $this->ebook->setAuthors($authors);
        $this->ebook->setDescription($this->descriptionToString($this->opf->getDcDescription()));
        $this->ebook->setDescriptionHtml($this->descriptionToHtml($this->opf->getDcDescription()));
        $this->ebook->setCopyright(! empty($this->opf->getDcRights()) ? implode(', ', $this->opf->getDcRights()) : null);
        $this->ebook->setPublisher($this->opf->getDcPublisher());
        $this->ebook->setIdentifiers($this->opf->getDcIdentifiers());
        $this->ebook->setPublishDate($this->opf->getDcDate());
        $this->ebook->setLanguage($this->opf->getDcLanguage());

        $tags = [];
        if (! empty($this->opf->getDcSubject())) {
            foreach ($this->opf->getDcSubject() as $subject) {
                if (strlen($subject) < 50) {
                    $tags[] = $subject;
                }
            }
        }
        $this->ebook->setTags($tags);

        $rating = null;
        if (! empty($this->opf->getMeta())) {
            foreach ($this->opf->getMeta() as $meta) {
                if ($meta->getName() === 'calibre:series') {
                    $this->ebook->setSeries($meta->getContent());
                }
                if ($meta->getName() === 'calibre:series_index') {
                    $this->ebook->setVolume((int) $meta->getContent());
                }
                if ($meta->getName() === 'calibre:rating') {
                    $rating = (float) $meta->getContent();
                }
            }
        }

        $contributor = ! empty($this->opf->getDcContributors()) ? implode(', ', $this->opf->getDcContributors()) : null;
        $this->ebook->setExtras([
            'contributor' => $contributor,
            'rating' => $rating,
        ]);

        if ($this->ebook->getSeries() && ! $this->ebook->getVolume()) {
            $this->ebook->setVolume(0);
        }

        return $this->ebook;
    }

    public function toCover(): ?EbookCover
    {
        if (! $this->coverPath || $this->ebook->getArchive() === null) {
            return null;
        }

        $file = $this->ebook->getArchive()->find($this->coverPath);
        $content = $this->ebook->getArchive()->getContent($file);

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
            $body = $html->getBody();
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
            if ($this->ebook->getArchive() === null) {
                continue;
            }
            $file = $this->ebook->getArchive()->find($path);
            if (! $file) {
                continue;
            }
            $html = $this->ebook->getArchive()->getContent($file);
            $items[] = EpubHtml::make($html, $file->getFilename());
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

    private function parseNcx(): ?NcxItem
    {
        $manifest = $this->opf->getManifest();
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

        if (! $path || $this->ebook->getArchive() === null) {
            return null;
        }

        $item = $this->ebook->getArchive()->find($path);
        $xml = $this->ebook->getArchive()->getContent($item);

        $ncx = NcxItem::make($xml);

        return $ncx;
    }

    public function getContainer(): ?EpubContainer
    {
        return $this->container;
    }

    public function getOpf(): ?OpfItem
    {
        return $this->opf;
    }

    public function getNcx(): ?NcxItem
    {
        if (is_null($this->ncx)) {
            $this->ncx = $this->parseNcx();
        }

        return $this->ncx;
    }

    public function getCoverPath(): ?string
    {
        return $this->coverPath;
    }

    public function getPagesCount(): ?int
    {
        if (is_null($this->pagesCount)) {
            $this->setCounts();
        }

        return $this->pagesCount;
    }

    public function getWordsCount(): ?int
    {
        if (is_null($this->wordsCount)) {
            $this->setCounts();
        }

        return $this->wordsCount;
    }

    /**
     * @return EpubChapter[]
     */
    public function getChapters(): array
    {
        if (empty($this->chapters)) {
            $this->parseChapters();
        }

        return $this->chapters;
    }

    /**
     * @return EpubHtml[]
     */
    public function getHtml(): array
    {
        if (empty($this->html)) {
            $this->parseFiles();
        }

        return $this->html;
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
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
}
