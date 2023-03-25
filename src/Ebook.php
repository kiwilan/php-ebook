<?php

namespace Kiwilan\Ebook;

use Kiwilan\Archive\Archive;
use Kiwilan\Ebook\Epub\EpubContainer;
use Kiwilan\Ebook\Epub\EpubOpf;

class Ebook
{
    protected ?EpubOpf $opf = null;

    protected ?EbookEntity $entity = null;

    protected function __construct(
        protected string $path,
        protected string $extension,
        protected Archive $archive,
    ) {
    }

    public static function make(string $path): self
    {
        $archive = Archive::make($path);
        $extension = $archive->extension();
        $self = new self($path, $extension, $archive);

        match ($self->extension) {
            'epub' => $self->epub(),
            default => throw new \Exception("Unknown archive type: {$extension}"),
        };

        return $self;
    }

    private function epub(): self
    {
        $container = EpubContainer::make($this->archiveToXml('container.xml'));
        $this->opf = EpubOpf::make($this->archiveToXml($container->opfPath()));

        $this->entity = EbookEntity::make($this->path);
        $this->entity->convertFromOpdf($this->opf);

        $cover = $this->archive->find($this->opf->coverPath());
        $coverContent = $this->archive->contentFile($cover->path());
        $this->entity->setCover($coverContent);

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
        $this->entity->setPageCount($pageCount);

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

    public function opf(): ?EpubOpf
    {
        return $this->opf;
    }

    public function entity(): ?EbookEntity
    {
        return $this->entity;
    }
}
