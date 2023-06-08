<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\Ebook\XmlReader;

/**
 * Transform `.ncx` file to an object.
 */
class NcxMetadata
{
    /** @var NcxMetadataHead[]|null */
    protected ?array $head = null;

    protected ?string $docTitle = null;

    /** @var NcxMetadataNavPoint[]|null */
    protected ?array $navPoints = null;

    protected ?string $version = null;

    protected ?string $lang = null;

    protected function __construct(
        protected array $xml,
    ) {
    }

    public static function make(string $content): self
    {
        $xml = XmlReader::toArray($content);

        $self = new self($xml);
        $self->head = $self->setHead();

        $docTitle = $xml['docTitle'] ?? null;
        if ($docTitle) {
            $docTitle = $docTitle['text'] ?? null;
        }
        $self->docTitle = $docTitle;

        $self->navPoints = $self->setNavPoints();
        usort($self->navPoints, fn (NcxMetadataNavPoint $a, NcxMetadataNavPoint $b) => $a->playOrder() <=> $b->playOrder());

        if (array_key_exists('@attributes', $xml)) {
            $self->version = $xml['@attributes']['version'] ?? null;
            $self->lang = $xml['@attributes']['lang'] ?? null;
        }

        return $self;
    }

    /**
     * @return NcxMetadataHead[]|null
     */
    private function setHead(): ?array
    {
        if (! array_key_exists('head', $this->xml)) {
            return null;
        }

        if (! array_key_exists('meta', $this->xml['head'])) {
            return null;
        }

        $head = [];
        foreach ($this->xml['head']['meta'] as $item) {
            $attributes = $item['@attributes'] ?? null;
            if (! $attributes) {
                continue;
            }

            $head[] = NcxMetadataHead::make($attributes);
        }

        return $head;
    }

    private function setNavPoints(): ?array
    {
        if (! array_key_exists('navMap', $this->xml)) {
            return null;
        }

        if (! array_key_exists('navPoint', $this->xml['navMap'])) {
            return null;
        }

        $navPoints = [];
        foreach ($this->xml['navMap']['navPoint'] as $item) {
            $navPoints[] = NcxMetadataNavPoint::make($item);
        }

        return $navPoints;
    }

    /**
     * @return NcxMetadataHead[]|null
     */
    public function head(): ?array
    {
        return $this->head;
    }

    public function docTitle(): ?string
    {
        return $this->docTitle;
    }

    /**
     * @return NcxMetadataNavPoint[]|null
     */
    public function navPoints(): ?array
    {
        return $this->navPoints;
    }

    public function version(): ?string
    {
        return $this->version;
    }

    public function lang(): ?string
    {
        return $this->lang;
    }

    public function toArray(): array
    {
        return [
            'head' => $this->head,
            'docTitle' => $this->docTitle,
            'navPoints' => $this->navPoints,
            'version' => $this->version,
            'lang' => $this->lang,
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

class NcxMetadataHead
{
    protected function __construct(
        protected ?string $name = null,
        protected ?string $content = null,
    ) {
    }

    public static function make(array $xml): self
    {
        $self = new self();
        $self->name = $xml['name'] ?? null;
        $self->content = $xml['content'] ?? null;

        return $self;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function content(): ?string
    {
        return $this->content;
    }
}

class NcxMetadataNavPoint
{
    protected function __construct(
        protected ?string $id = null,
        protected ?int $playOrder = null,
        protected ?string $label = null,
        protected ?string $src = null,
        protected ?string $class = null,
    ) {
    }

    public static function make(array $xml): self
    {
        $self = new self();

        $self->label = $xml['navLabel']['text'] ?? null;
        $self->src = $xml['content']['@attributes']['src'] ?? null;

        $attributes = $xml['@attributes'] ?? null;
        if (! $attributes) {
            return $self;
        }
        $self->id = $attributes['id'] ?? null;
        $playOrder = $attributes['playOrder'] ?? null;
        $self->playOrder = $playOrder ? intval($playOrder) : null;
        $self->class = $attributes['class'] ?? null;

        return $self;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function playOrder(): ?int
    {
        return $this->playOrder;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function src(): ?string
    {
        return $this->src;
    }
}
