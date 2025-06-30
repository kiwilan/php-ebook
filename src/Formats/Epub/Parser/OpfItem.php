<?php

namespace Kiwilan\Ebook\Formats\Epub\Parser;

use DateTime;
use DateTimeZone;
use Kiwilan\Ebook\Models\BookAuthor;
use Kiwilan\Ebook\Models\BookContributor;
use Kiwilan\Ebook\Models\BookIdentifier;
use Kiwilan\Ebook\Models\BookMeta;
use Kiwilan\Ebook\Utils\EbookUtils;
use Kiwilan\XmlReader\XmlReader;

/**
 * Transform `.opf` file to an object.
 */
class OpfItem
{
    protected array $metadata = [];

    protected array $manifest = [];

    protected array $spine = [];

    protected array $guide = [];

    protected ?int $epubVersion = null;
    protected ?string $epubVersionString = null;

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

    protected function __construct(
        protected XmlReader $xml,
    ) {}

    public static function make(string $content, ?string $filename = null): self
    {
        $xml = XmlReader::make($content);
        $self = new self($xml);

        $content = $xml->getContents();
        $self->epubVersion = $self->xml->getRootAttribute('version');
        $self->epubVersionString = $self->xml->getRootAttribute('version');
        $metadata = $content['metadata'] ?? $content['opf:metadata'] ?? [];
        $manifest = $content['manifest'] ?? $content['opf:manifest'] ?? [];

        $spine = $content['spine'] ?? [];
        $guide = $content['guide'] ?? [];

        $self->metadata = is_array($metadata) ? $metadata : [];
        $self->manifest = is_array($manifest) ? $manifest : [];
        $self->spine = is_array($spine) ? $spine : [];
        $self->guide = is_array($guide) ? $guide : [];
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
        $core = $this->xml->find($key) ?? null;

        return $this->parseNode($core);
    }

    private function parseNode(mixed $core): ?string
    {
        $value = XmlReader::parseContent($core);

        if (! is_string($value)) {
            return null;
        }

        return $value;
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
            $attributes = XmlReader::parseAttributes($item);
            $id = $attributes['id'] ?? null;

            if ($id && str_contains($id, 'cover')) {
                $href = $attributes['href'] ?? null;
                $extension = pathinfo($href, PATHINFO_EXTENSION);

                if (! in_array($extension, $extensionsAllowed)) {
                    continue;
                }

                $items[] = [
                    'id' => $id,
                    'href' => $href,
                    'media-type' => $attributes['media-type'] ?? null,
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

        $core = $this->manifest['item'] ?? $this->manifest['opf:item'] ?? null;

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

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getManifest(): array
    {
        return $this->manifest;
    }

    public function getSpine(): array
    {
        return $this->spine;
    }

    public function getGuide(): array
    {
        return $this->guide;
    }

    public function getEpubVersion(): ?int
    {
        return $this->epubVersion;
    }

    public function getEpubVersionString(): ?string
    {
        return $this->epubVersionString;
    }

    public function getDcTitle(): ?string
    {
        return $this->dcTitle;
    }

    /**
     * @return BookAuthor[]
     */
    public function getDcCreators(): array
    {
        return $this->dcCreators;
    }

    /**
     * @return BookContributor[]
     */
    public function getDcContributors(): array
    {
        return $this->dcContributors;
    }

    public function getDcDescription(): ?string
    {
        return $this->dcDescription;
    }

    public function getDcPublisher(): ?string
    {
        return $this->dcPublisher;
    }

    /**
     * @return BookIdentifier[]
     */
    public function getDcIdentifiers(): array
    {
        return $this->dcIdentifiers;
    }

    public function getDcDate(): ?DateTime
    {
        return $this->dcDate;
    }

    /**
     * @return string[]
     */
    public function getDcSubject(): array
    {
        return $this->dcSubject;
    }

    public function getDcLanguage(): ?string
    {
        return $this->dcLanguage;
    }

    /**
     * @deprecated Use `getMetaItems` instead.
     *
     * @return BookMeta[]
     */
    public function getMeta(): array
    {
        return $this->getMetaItems();
    }

    /**
     * @return BookMeta[]
     */
    public function getMetaItems(): array
    {
        $items = [];
        foreach ($this->meta as $item) {
            $items[$item->getName()] = $item;
        }

        return $items;
    }

    public function getMetaItem(string $key): ?BookMeta
    {
        $meta = array_filter($this->meta, fn (BookMeta $item) => $item->getName() === $key);

        return array_shift($meta);
    }

    public function getCoverPath(): ?string
    {
        return $this->coverPath;
    }

    /**
     * @return string[]
     */
    public function getDcRights(): array
    {
        return $this->dcRights;
    }

    /**
     * @return string[]
     */
    public function getContentFiles(): array
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

        $items = EbookUtils::parseStringWithSeperator($items);

        return $items;
    }

    private function setDcDate(): ?DateTime
    {
        $core = $this->xml->find('dc:date');

        if (! $core) {
            return null;
        }

        $core = XmlReader::parseContent($core);

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
        $core = $this->xml->find('dc:creator');

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $name = XmlReader::parseContent($item);
            // if `<dc:creator></dc:creator>`
            if (is_array($name)) {
                continue;
            }
            $attributes = XmlReader::parseAttributes($item);
            // remove `\n` and `\r` from the name
            $name = preg_replace('/\s+/', ' ', $name);
            $name = trim($name);

            $items[$name] = new BookAuthor(
                name: $name,
                role: $attributes['role'] ?? null,
            );
        }

        return $items;
    }

    /**
     * @return BookContributor[]
     */
    private function setDcContributors(): array
    {
        $core = $this->xml->find('dc:contributor');

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            if (is_string($item)) {
                $item = ['@content' => $item];
            }
            $items[] = new BookContributor(
                contents: XmlReader::parseContent($item),
                role: XmlReader::parseAttributes($item)['role'] ?? null,
            );
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function setDcRights(): array
    {
        $core = $this->xml->find('dc:rights');

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
                $item = ['@content' => $item];
            }

            $content = XmlReader::parseContent($item);

            if (is_array($content)) {
                $content = implode(' ', $content);
            }

            if (! empty($content)) {
                $items[] = $content;
            }
        }

        return $items;
    }

    /**
     * @return BookIdentifier[]
     */
    private function setDcIdentifiers(): array
    {
        $core = $this->xml->find('dc:identifier');

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $value = XmlReader::parseContent($item);
            $scheme = XmlReader::parseAttributes($item)['scheme'] ?? null;
            $identifier = new BookIdentifier(
                value: $value,
                scheme: $scheme,
            );
            $items[$identifier->getScheme()] = $identifier;
        }

        return $items;
    }

    /**
     * @return BookMeta[]
     */
    private function setMeta(): array
    {
        $core = $this->xml->find('meta', strict: true);

        if (! $core) {
            return [];
        }

        $core = $this->multipleItems($core);
        $items = [];

        foreach ($core as $item) {
            $items[] = new BookMeta(
                name: $item['@attributes']['name'] ?? null,
                contents: $item['@attributes']['content'] ?? null,
            );
        }

        return $items;
    }

    private function multipleItems(mixed $items): array
    {
        if (! is_array($items)) {
            $items = [$items];
        }

        $core = $items;
        // Check if subarrays exists
        $isMultiple = array_key_exists(0, $items);

        if (! $isMultiple) {
            $content = XmlReader::parseContent($items);
            $attr = XmlReader::parseAttributes($items);

            // Check if bad multiple creators `Jean M. Auel, Philippe Rouard` exists
            $content = EbookUtils::parseStringWithSeperator($content);

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

            $core = $temp;
        }

        return $core;
    }

    public function toArray(): array
    {
        return [
            'epubVersion' => $this->epubVersion,
            'dcTitle' => $this->dcTitle,
            'dcCreators' => array_map(fn (BookAuthor $creator) => $creator->toArray(), $this->dcCreators),
            'dcContributors' => array_map(fn (BookContributor $contributor) => $contributor->toArray(), $this->dcContributors),
            'dcDescription' => $this->dcDescription,
            'dcPublisher' => $this->dcPublisher,
            'dcIdentifiers' => array_map(fn (BookIdentifier $identifier) => $identifier->toArray(), $this->dcIdentifiers),
            'dcDate' => $this->dcDate,
            'dcSubject' => $this->dcSubject,
            'dcLanguage' => $this->dcLanguage,
            'meta' => array_map(fn (BookMeta $meta) => $meta->toArray(), $this->meta),
            'coverPath' => $this->coverPath,
            'dcRights' => $this->dcRights,
            'contentFiles' => $this->contentFiles,
            'raw' => $this->xml->toArray(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function __toString(): string
    {
        $creators = array_map(fn (BookAuthor $creator) => $creator->getName(), $this->dcCreators);
        $creators = implode(', ', $creators);

        return "{$this->dcTitle} by {$creators}";
    }
}
