<?php

namespace Kiwilan\Ebook\Epub;

use Kiwilan\Ebook\BookEntity;
use Kiwilan\Ebook\Ebook;

class EpubMetadata
{
    protected ?EpubContainer $container = null;

    protected ?OpfMetadata $opf = null;

    protected ?NcxMetadata $toc = null;

    protected string $filename;

    protected ?string $coverPath = null;

    protected int $pageCount = 0;

    protected int $wordsCount = 0;

    /** @var EpubHtml[] */
    protected array $html = [];

    /** @var string[] */
    protected array $files = [];

    public static function make(Ebook $ebook): self
    {
        $self = new self();
        $self->filename = $ebook->filename();
        $containerXml = $ebook->archiveToXml('container.xml');

        if (! $containerXml) {
            return $self;
        }

        $self->container = EpubContainer::make($containerXml);

        $opfXml = $ebook->archiveToXml($self->container->opfPath());
        if (! $opfXml) {
            return $self;
        }

        $self->opf = OpfMetadata::make($opfXml, $self->filename());
        $self->coverPath = $self->opf->coverPath();
        $self->files = $self->opf->contentFiles();
        $self->setHtmlAndCounts($ebook);

        $self->toc = $self->setNcx($ebook);

        return $self;
    }

    public function toBook(): BookEntity
    {
        $book = BookEntity::make();

        $altTitle = explode('.', $this->filename);
        $altTitle = $altTitle[0] ?? 'untitled';
        $book->setTitle($this->opf->dcTitle() ?? $altTitle);

        $authors = array_values($this->opf->dcCreators());
        $book->setAuthorFirst($authors[0] ?? null);
        $book->setAuthors($authors);
        if ($this->opf->dcDescription()) {
            $book->setDescription(strip_tags($this->opf->dcDescription()));
        }
        $book->setContributor(! empty($this->opf->dcContributors()) ? implode(', ', $this->opf->dcContributors()) : null);
        $book->setRights(! empty($this->opf->dcRights()) ? implode(', ', $this->opf->dcRights()) : null);
        $book->setPublisher($this->opf->dcPublisher());
        $book->setIdentifiers($this->opf->dcIdentifiers());
        $book->setDate($this->opf->dcDate());
        $book->setLanguage($this->opf->dcLanguage());

        $tags = [];
        if (! empty($this->opf->dcSubject())) {
            foreach ($this->opf->dcSubject() as $subject) {
                if (strlen($subject) < 50) {
                    $tags[] = $subject;
                }
            }
        }
        $book->setTags($tags);

        if (! empty($this->opf->meta())) {
            foreach ($this->opf->meta() as $meta) {
                if ($meta->name() === 'calibre:series') {
                    $book->setSeries($meta->content());
                }
                if ($meta->name() === 'calibre:series_index') {
                    $book->setVolume((int) $meta->content());
                }
                if ($meta->name() === 'calibre:rating') {
                    $book->setRating((int) $meta->content());
                }
            }
        }

        return $book;
    }

    private function setHtmlAndCounts(Ebook $ebook)
    {
        foreach ($this->files as $path) {
            $file = $ebook->archive()->find($path);
            $html = $ebook->archive()->content($file);
            $this->html[] = EpubHtml::make($html, $file->filename());

            $content = strip_tags($html);
            $content = preg_replace('/[\r\n|\n|\r)]+/', '', $content);
            $words = str_word_count($content, 1);

            $this->wordsCount += count($words);
        }

        $this->pageCount = (int) ceil($this->wordsCount / Ebook::wordsByPage());
    }

    private function setNcx(Ebook $ebook): ?NcxMetadata
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

        $file = $ebook->archive()->find($path);
        $ncxXml = $ebook->archive()->content($file);

        return NcxMetadata::make($ncxXml);

    }

    public function container(): ?EpubContainer
    {
        return $this->container;
    }

    public function opf(): ?OpfMetadata
    {
        return $this->opf;
    }

    public function toc(): ?NcxMetadata
    {
        return $this->toc;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function coverPath(): ?string
    {
        return $this->coverPath;
    }

    public function pageCount(): int
    {
        return $this->pageCount;
    }

    public function wordsCount(): int
    {
        return $this->wordsCount;
    }

    /**
     * @return EpubHtml[]
     */
    public function html(): array
    {
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
            'toc' => $this->toc?->toArray(),
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
