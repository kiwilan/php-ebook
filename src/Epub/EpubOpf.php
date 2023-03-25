<?php

namespace Kiwilan\Ebook\Epub;

use DateTime;
use DateTimeZone;
use Kiwilan\Ebook\EbookXmlReader;
use Kiwilan\Ebook\Entity\EntityContributor;
use Kiwilan\Ebook\Entity\EntityCreator;
use Kiwilan\Ebook\Entity\EntityIdentifier;
use Kiwilan\Ebook\Entity\EntityMeta;

class EpubOpf
{
    protected array $metadata = [];

    protected array $manifest = [];

    protected array $spine = [];

    protected array $guide = [];

    protected ?int $epubVersion = null;

    protected ?string $dcTitle = null;

    /** @var EntityCreator[] */
    protected array $dcCreators = [];

    /** @var EntityContributor[] */
    protected array $dcContributors = [];

    protected ?string $dcDescription = null;

    protected ?string $dcPublisher = null;

    /** @var EntityIdentifier[] */
    protected array $dcIdentifiers = [];

    protected ?DateTime $dcDate = null;

    /** @var string[] */
    protected array $dcSubject = [];

    protected ?string $dcLanguage = null;

    protected array $dcRights = [];

    /** @var EntityMeta[] */
    protected array $meta = [];

    protected ?string $coverPath = null;

    /** @var string[] */
    protected array $contentFiles = [];

    protected function __construct(
    ) {
    }

    public static function make(string $content): self
    {
        $xml = EbookXmlReader::make($content);
        $self = new self();

        $self->epubVersion = $xml['@attributes']['version'] ?? null;
        $self->metadata = $xml['metadata'] ?? [];
        $self->manifest = $xml['manifest'] ?? [];
        $self->spine = $xml['spine'] ?? [];
        $self->guide = $xml['guide'] ?? [];

        $self->parseMetadata();
        $self->coverPath = $self->findCover();
        $self->contentFiles = $self->findContent();

        return $self;
    }

    private function parseMetadata(): self
    {
        if (empty($this->metadata)) {
            return $this;
        }

        $this->dcTitle = $this->parseMetadataNode('dc:title');
        $this->dcDescription = $this->parseMetadataNode('dc:description');
        $this->dcPublisher = $this->parseMetadataNode('dc:publisher');
        $this->dcLanguage = $this->parseMetadataNode('dc:language');

        $this->dcSubject = $this->setDcSubjects();
        $this->dcDate = $this->setDcDate();
        $this->dcCreators = $this->setDcCreators();
        $this->dcContributors = $this->setDcContributors();
        $this->dcIdentifiers = $this->setDcIdentifiers();
        $this->dcRights = $this->setDcRights();
        $this->meta = $this->setMeta();

        return $this;
    }

    private function parseMetadataNode(string $key): ?string
    {
        $data = $this->metadata[$key] ?? null;

        return $this->parseNode($data);
    }

    private function parseNode(mixed $data): ?string
    {
        if (is_string($data)) {
            return $data;
        }

        if (is_array($data)) {
            return $data['@content'] ?? null;
        }

        return null;
    }

    private function findCover(): ?string
    {
        if (empty($this->manifest)) {
            return null;
        }

        $data = $this->manifest['item'] ?? null;

        if (empty($data)) {
            return null;
        }

        $path = null;
        foreach ($data as $item) {
            $id = $item['@attributes']['id'] ?? null;
            if ($id && str_contains($id, 'cover')) {
                $path = $item['@attributes']['href'] ?? null;
            }
        }

        return $path;
    }

    /**
     * @return string[]
     */
    private function findContent(): array
    {
        if (empty($this->manifest)) {
            return [];
        }

        $data = $this->manifest['item'] ?? null;

        if (empty($data)) {
            return [];
        }

        $files = [];
        foreach ($data as $item) {
            $mediaType = $item['@attributes']['media-type'] ?? null;
            if ($mediaType && str_contains($mediaType, 'html')) {
                $files[] = $item['@attributes']['href'] ?? null;
            }
        }

        return $files;
    }

    public function epubVersion(): ?int
    {
        return $this->epubVersion;
    }

    public function dcTitle(): ?string
    {
        return $this->dcTitle;
    }

    /**
     * @return EntityCreator[]
     */
    public function dcCreators(): array
    {
        return $this->dcCreators;
    }

    /**
     * @return EntityContributor[]
     */
    public function dcContributors(): array
    {
        return $this->dcContributors;
    }

    public function dcDescription(): ?string
    {
        return $this->dcDescription;
    }

    public function dcPublisher(): ?string
    {
        return $this->dcPublisher;
    }

    /**
     * @return EntityIdentifier[]
     */
    public function dcIdentifiers(): array
    {
        return $this->dcIdentifiers;
    }

    public function dcDate(): ?DateTime
    {
        return $this->dcDate;
    }

    /**
     * @return string[]
     */
    public function dcSubject(): array
    {
        return $this->dcSubject;
    }

    public function dcLanguage(): ?string
    {
        return $this->dcLanguage;
    }

    /**
     * @return EntityMeta[]
     */
    public function meta(): array
    {
        return $this->meta;
    }

    public function coverPath(): ?string
    {
        return $this->coverPath;
    }

    /**
     * @return string[]
     */
    public function dcRights(): array
    {
        return $this->dcRights;
    }

    /**
     * @return string[]
     */
    public function contentFiles(): array
    {
        return $this->contentFiles;
    }

    private function setDcSubjects(): array
    {
        $data = $this->metadata['dc:subject'] ?? null;

        if (! $data) {
            return [];
        }

        $items = [];

        if (is_string($data)) {
            $items = [$data];
        } else {
            $items = $data;
        }

        return $items;
    }

    private function setDcDate(): ?DateTime
    {
        $data = $this->metadata['dc:date'] ?? null;

        if (! $data) {
            return null;
        }

        try {
            $date = new DateTime($data, new DateTimeZone('UTC'));
        } catch (\Throwable $th) {
            return null;
        }

        if (! $date instanceof DateTime) {
            return null;
        }

        return $date;
    }

    /**
     * @return EntityCreator[]
     */
    private function setDcCreators(): array
    {
        $data = $this->metadata['dc:creator'] ?? null;
        if (! $data) {
            return [];
        }

        $data = $this->multipleItems($data);
        $items = [];

        foreach ($data as $item) {
            $name = $item['@content'];
            $items[$name] = new EntityCreator(
                name: $name,
                role: $item['@attributes']['role'] ?? null,
            );
        }

        return $items;
    }

    /**
     * @return EntityContributor[]
     */
    private function setDcContributors(): array
    {
        $data = $this->metadata['dc:contributor'] ?? null;
        if (! $data) {
            return [];
        }

        $data = $this->multipleItems($data);
        $items = [];

        foreach ($data as $item) {
            if (is_string($item)) {
                $item = ['@content' => $item];
            }
            $items[] = new EntityContributor(
                content: $item['@content'],
                role: $item['@attributes']['role'] ?? null,
            );
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function setDcRights(): array
    {
        $data = $this->metadata['dc:rights'] ?? null;
        if (! $data) {
            return [];
        }

        // $data = $this->multipleItems($data);
        // TODO
        $items = [];

        // foreach ($data as $item) {
        //     $items[] = $item['@content'];
        // }

        return $items;
    }

    /**
     * @return EntityIdentifier[]
     */
    private function setDcIdentifiers(): array
    {
        $data = $this->metadata['dc:identifier'] ?? null;
        if (! $data) {
            return [];
        }

        $data = $this->multipleItems($data);
        $items = [];

        foreach ($data as $item) {
            $content = $item['@content'] ?? null;
            $type = $item['@attributes']['scheme'] ?? null;
            $identifier = new EntityIdentifier(
                content: $content,
                type: $type,
            );
            $identifier->parse();
            $items[$identifier->type()] = $identifier;
        }

        return $items;
    }

    /**
     * @return EntityMeta[]
     */
    private function setMeta(): array
    {
        $data = $this->metadata['meta'] ?? null;
        if (! $data) {
            return [];
        }

        $data = $this->multipleItems($data);
        $items = [];

        foreach ($data as $item) {
            $items[] = new EntityMeta(
                name: $item['@attributes']['name'] ?? null,
                content: $item['@attributes']['content'] ?? null,
            );
        }

        return $items;
    }

    /**
     * Good multiple creators: `Terry Pratchett & Stephen Baxter`.
     *
     * ```php
     * [
     *   [
     *     "@content" => "Terry Pratchett"
     *     "@attributes" => [
     *       "role" => "aut"
     *       "file-as" => "Pratchett, Terry & Baxter, Stephen"
     *     ]
     *   ],
     *   [
     *     "@content" => "Stephen Baxter"
     *     "@attributes" => array:1 [
     *       "role" => "aut"
     *     ]
     *   ]
     * ]
     * ```
     *
     * Bad multiple creators: `Jean M. Auel, Philippe Rouard`.
     *
     * ```php
     * [
     *   "@content" => "Jean M. Auel, Philippe Rouard"
     *   "@attributes" => array:2 [
     *     "role" => "aut"
     *     "file-as" => "Jean M. Auel, Philippe Rouard"
     *   ]
     * ]
     * ```
     */
    private function multipleItems(array $items): array
    {
        $data = $items;
        // Check if subarrays exists
        $isMultiple = array_key_exists(0, $items);

        if (! $isMultiple) {
            $content = $items['@content'] ?? null;
            $attr = $items['@attributes'] ?? null;

            // Check if bad multiple creators `Jean M. Auel, Philippe Rouard` exists
            if (str_contains($content, ',')) {
                $content = explode(',', $content);
                $content = array_map('trim', $content);
            }

            $temp = [];
            // If bad multiple creators exists
            if (is_array($content)) {
                foreach ($content as $item) {
                    $temp[] = [
                        '@content' => $item,
                        '@attributes' => $attr,
                    ];
                }
            } else {
                // otherwise create a new array
                $temp[] = $items;
            }

            $data = $temp;
        }

        return $data;
    }
}
