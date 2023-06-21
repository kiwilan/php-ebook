<?php

namespace Kiwilan\Ebook\Formats\Epub;

use Kiwilan\XmlReader\XmlReader;

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
        protected XmlReader $xml,
    ) {
    }

    public static function make(string $content): self
    {
        $xml = XmlReader::make($content);

        $self = new self($xml);
        $self->head = $self->setHead();

        $docTitle = $self->xml->search('docTitle');

        if ($docTitle) {
            $docTitle = $docTitle['text'] ?? null;
        }
        $self->docTitle = $docTitle;

        $self->navPoints = $self->setNavPoints();

        if (is_array($self->navPoints)) {
            usort($self->navPoints, fn (NcxMetadataNavPoint $a, NcxMetadataNavPoint $b) => $a->playOrder() <=> $b->playOrder());
        }

        $self->version = $xml->rootAttribute('version');
        $self->lang = $xml->rootAttribute('lang');

        return $self;
    }

    /**
     * @return NcxMetadataHead[]|null
     */
    private function setHead(): ?array
    {
        $ncx = $this->xml->content();

        if (! array_key_exists('head', $ncx)) {
            return null;
        }

        if (! array_key_exists('meta', $ncx['head'])) {
            return null;
        }

        $head = [];

        foreach ($ncx['head']['meta'] as $item) {
            $attributes = XmlReader::getAttributes($item) ?? null;

            if (! $attributes) {
                continue;
            }

            $head[] = NcxMetadataHead::make($attributes);
        }

        return $head;
    }

    private function setNavPoints(): ?array
    {
        $navMap = $this->xml->search('navMap');
        $navPoint = $this->xml->search('navPoint');

        if (! $navPoint || ! $navMap) {
            return null;
        }

        $navPoints = [];

        foreach ($navPoint as $item) {
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
            'head' => array_map(fn (NcxMetadataHead $item) => $item->toArray(), $this->head),
            'docTitle' => $this->docTitle,
            'navPoints' => array_map(fn (NcxMetadataNavPoint $item) => $item->toArray(), $this->navPoints),
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

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'content' => $this->content,
        ];
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'playOrder' => $this->playOrder,
            'label' => $this->label,
            'src' => $this->src,
            'class' => $this->class,
        ];
    }
}
