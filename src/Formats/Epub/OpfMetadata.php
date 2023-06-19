<?php

namespace Kiwilan\Ebook\Formats\Epub;

use DateTime;
use DateTimeZone;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookContributor;
use Kiwilan\Ebook\Tools\BookIdentifier;
use Kiwilan\Ebook\Tools\BookMeta;
use Kiwilan\XmlReader\XmlReader;

/**
 * Transform `.opf` file to an object.
 */
class OpfMetadata
{
    protected array $metadata = [];

    protected array $manifest = [];

    protected array $spine = [];

    protected array $guide = [];

    protected ?int $epubVersion = null;

    protected ?string $filename = null;

    protected ?string $dcTitle = null;

    /** @var BookAuthor[] */
    protected array $dcCreators = [];

    /** @var BookContributor[] */
    protected array $dcContributors = [];

    protected ?string $dcDescription = null;

    protected ?string $dcPublisher = null;

    /** @var BookIdentifier[] */
    protected array $dcIdentifiers = [];

    protected ?DateTime $dcDate = null;

    /** @var string[] */
    protected array $dcSubject = [];

    protected ?string $dcLanguage = null;

    protected array $dcRights = [];

    /** @var BookMeta[] */
    protected array $meta = [];

    protected ?string $coverPath = null;

    /** @var string[] */
    protected array $contentFiles = [];

    public static function make(string $content, string $filename): self
    {
        $xml = XmlReader::make($content)->content();
        $self = new self();

        $package = $xml['package'] ?? [];
        $self->epubVersion = $package['@attributes']['version'] ?? null;
        $self->metadata = $package['metadata'] ?? [];
        $self->manifest = $package['manifest'] ?? [];
        $self->spine = $package['spine'] ?? [];
        $self->guide = $package['guide'] ?? [];
        $self->filename = $filename;

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
        $core = $this->metadata[$key] ?? null;

        return $this->parseNode($core);
    }

    private function parseNode(mixed $core): ?string
    {
        if (is_string($core)) {
            return $core;
        }

        if (is_array($core)) {
            return $core['_value'] ?? null;
        }

        return null;
    }

    private function findCover(): ?string
    {
        if (empty($this->manifest)) {
            return null;
        }

        $core = $this->manifest['item'] ?? null;

        if (empty($core)) {
            return null;
        }

        $items = [];
        $extensionsAllowed = ['jpg', 'jpeg', 'png'];

        foreach ($core as $item) {
            $id = $item['@attributes']['id'] ?? null;

            if ($id && str_contains($id, 'cover')) {
                $href = $item['@attributes']['href'] ?? null;
                $extension = pathinfo($href, PATHINFO_EXTENSION);

                if (! in_array($extension, $extensionsAllowed)) {
                    continue;
                }

                $items[] = [
                    'id' => $id,
                    'href' => $href,
                    'media-type' => $item['@attributes']['media-type'] ?? null,
                ];
            }
        }

        if (count($items) === 1) {
            return $items[0]['href'];
        }

        $path = null;

        foreach ($items as $item) {
            if (! str_contains($item['href'], '/')) {
                $path = $item['href'];

                break;
            }

            $path = $item['href'];
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

        $core = $this->manifest['item'] ?? null;

        if (empty($core)) {
            return [];
        }

        $files = [];

        foreach ($core as $item) {
            $mediaType = $item['@attributes']['media-type'] ?? null;

            if ($mediaType && str_contains($mediaType, 'html')) {
                $files[] = $item['@attributes']['href'] ?? null;
            }
        }

        return $files;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function manifest(): array
    {
        return $this->manifest;
    }

    public function spine(): array
    {
        return $this->spine;
    }

    public function guide(): array
    {
        return $this->guide;
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
     * @return BookAuthor[]
     */
    public function dcCreators(): array
    {
        return $this->dcCreators;
    }

    /**
     * @return BookContributor[]
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
     * @return BookIdentifier[]
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
     * @return BookMeta[]
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
        $core = $this->metadata['dc:subject'] ?? null;

        if (! $core) {
            return [];
        }

        $items = [];

        if (is_string($core)) {
            $items = [$core];
        } else {
            $items = $core;
        }

        $temp = [];
        foreach ($items as $item) {
            $temp[] = $item['_value'] ?? null;
        }
        $items = $temp;

        return $items;
    }

    private function setDcDate(): ?DateTime
    {
        $core = $this->metadata['dc:date'] ?? null;

        if (! $core) {
            return null;
        }

        $core = $core['_value'] ?? null;

        try {
            $date = new DateTime($core, new DateTimeZone('UTC'));
        } catch (\Throwable $th) {
            return null;
        }

        if (! $date instanceof DateTime) {
            return null;
        }

        return $date;
    }

    /**
     * @return BookAuthor[]
     */
    private function setDcCreators(): array
    {
        $core = $this->metadata['dc:creator'] ?? null;

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $name = $item['_value'];
            $items[$name] = new BookAuthor(
                name: $name,
                role: $item['@attributes']['role'] ?? null,
            );
        }

        return $items;
    }

    /**
     * @return BookContributor[]
     */
    private function setDcContributors(): array
    {
        $core = $this->metadata['dc:contributor'] ?? null;

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            if (is_string($item)) {
                $item = ['_value' => $item];
            }
            $items[] = new BookContributor(
                content: $item['_value'],
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
        $core = $this->metadata['dc:rights'] ?? null;

        if (! $core) {
            return [];
        }

        if (is_string($core)) {
            $core = [$core];
        }
        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            if (is_string($item)) {
                $item = ['_value' => $item];
            }
            $items[] = $item['_value'];
        }

        return $items;
    }

    /**
     * @return BookIdentifier[]
     */
    private function setDcIdentifiers(): array
    {
        $core = $this->metadata['dc:identifier'] ?? null;

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $value = $item['_value'] ?? null;
            $scheme = $item['@attributes']['scheme'] ?? null;
            $identifier = new BookIdentifier(
                value: $value,
                scheme: $scheme,
            );
            $identifier->parse();
            $items[$identifier->scheme()] = $identifier;
        }

        return $items;
    }

    /**
     * @return BookMeta[]
     */
    private function setMeta(): array
    {
        $core = $this->metadata['meta'] ?? null;

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $items[] = new BookMeta(
                name: $item['@attributes']['name'] ?? null,
                content: $item['@attributes']['content'] ?? null,
            );
        }

        return $items;
    }

    private function multipleItems(array $items): array
    {
        $core = $items;
        // Check if subarrays exists
        $isMultiple = array_key_exists(0, $items);

        if (! $isMultiple) {
            $content = $items['_value'] ?? null;
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
                        '_value' => $item,
                        '@attributes' => $attr,
                    ];
                }
            } else {
                // otherwise create a new array
                $temp[] = $items;
            }

            $core = $temp;
        }

        return $core;
    }

    public function toArray(): array
    {
        return [
            'epubVersion' => $this->epubVersion,
            'dcTitle' => $this->dcTitle,
            'dcCreators' => $this->dcCreators,
            'dcContributors' => $this->dcContributors,
            'dcDescription' => $this->dcDescription,
            'dcPublisher' => $this->dcPublisher,
            'dcIdentifiers' => $this->dcIdentifiers,
            'dcDate' => $this->dcDate,
            'dcSubject' => $this->dcSubject,
            'dcLanguage' => $this->dcLanguage,
            'meta' => $this->meta,
            'coverPath' => $this->coverPath,
            'dcRights' => $this->dcRights,
            'contentFiles' => $this->contentFiles,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function __toString(): string
    {
        $creators = array_map(fn (BookAuthor $creator) => $creator->name(), $this->dcCreators);
        $creators = implode(', ', $creators);

        return "{$this->dcTitle} by {$creators}";
    }
}
