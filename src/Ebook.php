<?php

namespace Kiwilan\Ebook;

use Kiwilan\Archive\Archive;
use Kiwilan\Archive\ArchivePdf;
use Kiwilan\Ebook\Cba\CbaXml;
use Kiwilan\Ebook\Entity\BookCreator;
use Kiwilan\Ebook\Epub\EpubContainer;
use Kiwilan\Ebook\Epub\EpubOpf;

class Ebook
{
    protected ?EpubOpf $opf = null;

    protected ?EbookEntity $book = null;

    protected ?Archive $archive = null;

    protected ?string $format = null; // epub, pdf, cba

    protected ?ArchivePdf $pdf = null;

    protected function __construct(
        protected string $path,
        protected string $extension,
    ) {
    }

    public static function make(string $path): self
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ($extension && ! in_array($extension, ['epub', 'pdf', 'cbz', 'cbr', 'cb7'])) {
            throw new \Exception("Unknown archive type: {$extension}");
        }

        $self = new self($path, $extension);
        if ($extension === 'pdf') {
            $self->pdf = ArchivePdf::make($path);
            $self->format = 'pdf';
        } else {
            $self->archive = Archive::make($path);
            if (in_array($extension, ['cbz', 'cbr', 'cb7', 'cbt'])) {
                $self->format = 'cba';
            } else {
                $self->format = 'epub';
            }
        }

        match ($self->format) {
            'epub' => $self->epub(),
            'cba' => $self->cba(),
            'pdf' => $self->pdf(),
        };

        return $self;
    }

    private function epub(): self
    {
        $container = EpubContainer::make($this->archiveToXml('container.xml'));
        $this->opf = EpubOpf::make($this->archiveToXml($container->opfPath()));

        $this->book = EbookEntity::make($this->path);
        $this->book->convertFromOpdf($this->opf);

        $cover = $this->archive->find($this->opf->coverPath());
        $coverContent = $this->archive->contentFile($cover->path());
        $this->book->setCover($coverContent);

        $count = 0;
        foreach ($this->opf->contentFiles() as $path) {
            $file = $this->archive->find($path);
            $content = $this->archive->contentFile($file->path());
            $content = strip_tags($content);
            $content = preg_replace('/[\r\n|\n|\r)]+/', '', $content);
            $words = str_word_count($content, 1);

            $count += count($words);
        }
        $pageCount = (int) ceil($count / 250);
        $this->book->setPageCount($pageCount);

        return $this;
    }

    private function cba(): self
    {
        $this->book = EbookEntity::make($this->path);
        $cba = CbaXml::make($this->archiveToXml('ComicInfo.xml'));

        $authors = [];
        $authors[] = new BookCreator($cba->writer());

        $this->book()->setTitle($cba->title());
        $this->book()->setSeries($cba->series());
        $this->book()->setVolume($cba->number());
        $this->book()->setAuthors($authors);
        $this->book()->setPublisher($cba->publisher());
        $this->book()->setLanguage($cba->languageIso());

        return $this;
    }

    private function pdf(): self
    {
        $this->book = EbookEntity::make($this->path);
        $this->book->setTitle($this->pdf->metadata()->title());

        $author = $this->pdf->metadata()->author();
        $authors = [];
        if (str_contains($author, ',')) {
            $authors = explode(',', $author);
        } elseif (str_contains($author, '&')) {
            $authors = explode(',', $author);
        } elseif (str_contains($author, 'and')) {
            $authors = explode(',', $author);
        } else {
            $authors[] = $author;
        }

        $creators = [];
        foreach ($authors as $author) {
            $creators[] = new BookCreator(
                name: trim($author),
            );
        }

        $this->book->setAuthors($creators);
        $this->book->setDescription($this->pdf->metadata()->subject());
        $this->book->setPublisher($this->pdf->metadata()->creator());
        $this->book->setTags($this->pdf->metadata()->keywords());
        $this->book->setDate($this->pdf->metadata()->creationDate());
        $this->book->setPageCount($this->pdf->metadata()->pages());

        return $this;
    }

    private function archiveToXml(string $path): ?string
    {
        $file = $this->archive->find($path);
        $content = $this->archive->contentFile($file->path());

        return $content;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function extension(): string
    {
        return $this->extension;
    }

    public function format(): ?string
    {
        return $this->format;
    }

    public function opf(): ?EpubOpf
    {
        return $this->opf;
    }

    public function book(): ?EbookEntity
    {
        return $this->book;
    }
}
