<?php

namespace Kiwilan\Ebook\Formats\Fb2\Parser;

use Kiwilan\XmlReader\XmlReader;

class Fb2Parser
{
    protected function __construct(
        protected string $path,
        protected XmlReader $xml,
        protected ?string $root = null,
        protected array $metadata = [],

        protected array $titleInfo = [],
        protected array $documentInfo = [],
        protected array $publishInfo = [],
        protected ?string $cover = null,
    ) {
    }

    public static function make(string $path): self
    {
        $contents = file_get_contents($path);
        $xml = XmlReader::make($contents);

        $self = new self(
            path: $path,
            xml: $xml,
        );

        $self->root = $xml->getRoot();
        $contents = $xml->getContent();
        if (array_key_exists('description', $contents)) {
            $self->metadata = $contents['description'];

            if (array_key_exists('title-info', $self->metadata)) {
                $self->titleInfo = $self->metadata['title-info'];

                $coverPage = $self->titleInfo['coverpage'] ?? null;
                if ($coverPage) {
                    $cover = $coverPage['image'] ?? null;
                    if ($cover) {
                        $id = $cover['@attributes']['href'] ?? null;
                        $id = str_replace('#', '', $id);

                        $binary = $xml->getContent()['binary'] ?? null;
                        if ($binary) {
                            foreach ($binary as $item) {
                                $attributes = $item['@attributes'] ?? null;
                                $binaryId = $attributes['id'] ?? null;
                                if ($binaryId === $id) {
                                    $cover = $item['@content'] ?? null;
                                    $cover = str_replace("\n", '', $cover);
                                    $self->cover = $cover;
                                }
                            }
                        }
                    }
                }
            }

            if (array_key_exists('document-info', $self->metadata)) {
                $self->documentInfo = $self->metadata['document-info'];
            }

            if (array_key_exists('publish-info', $self->metadata)) {
                $self->publishInfo = $self->metadata['publish-info'];
            }
        }

        return $self;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getTitleInfo(): array
    {
        return $this->titleInfo;
    }

    public function getDocumentInfo(): array
    {
        return $this->documentInfo;
    }

    public function getPublishInfo(): array
    {
        return $this->publishInfo;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }
}
