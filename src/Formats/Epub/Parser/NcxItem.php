<?php

namespace Kiwilan\Ebook\Formats\Epub\Parser;

use Kiwilan\XmlReader\XmlReader;

/**
 * Transform `.ncx` file to an object.
 */
class NcxItem
{
    /** @var NcxItemHead[]|null */
    protected ?array $head = null;

    protected ?string $docTitle = null;

    /** @var NcxItemNavPoint[]|null */
    protected ?array $navPoints = null;

    protected ?string $version = null;

    protected ?string $lang = null;

    protected function __construct(
        protected XmlReader $xml,
    ) {}

    public static function make(string $content): self
    {
        try {
            $xml = XmlReader::make($content);
        } catch (\Throwable $th) {
            throw new \Exception('XML can\'t be read, file could be encrypted.');
        }

        $self = new self($xml);
        $self->head = $self->setHead();

        $docTitle = $self->xml->find('docTitle');

        if ($docTitle) {
            $docTitle = $docTitle['text'] ?? null;
        }
        $self->docTitle = $docTitle;

        $self->navPoints = $self->setNavPoints();

        if (is_array($self->navPoints)) {
            usort($self->navPoints, fn (NcxItemNavPoint $a, NcxItemNavPoint $b) => $a->getPlayOrder() <=> $b->getPlayOrder());
        }

        $self->version = $xml->getRootAttribute('version');
        $self->lang = $xml->getRootAttribute('lang');

        return $self;
    }

    /**
     * @return NcxItemHead[]|null
     */
    private function setHead(): ?array
    {
        $ncx = $this->xml->getContents();

        if (! array_key_exists('head', $ncx)) {
            return null;
        }

        if (! array_key_exists('meta', $ncx['head'])) {
            return null;
        }

        $head = [];

        foreach ($ncx['head']['meta'] as $item) {
            $attributes = XmlReader::parseAttributes($item) ?? null;

            if (! $attributes) {
                continue;
            }

            $head[] = NcxItemHead::make($attributes);
        }

        return $head;
    }

    private function setNavPoints(): ?array
    {
        $navMap = $this->xml->find('navMap');
        $navPoint = $this->xml->find('navPoint');

        if (! $navPoint || ! $navMap) {
            return null;
        }

        $navPoints = [];

        foreach ($navPoint as $item) {
            $navPoints[] = NcxItemNavPoint::make($item);
        }

        return $navPoints;
    }

    /**
     * @return NcxItemHead[]|null
     */
    public function getHead(): ?array
    {
        return $this->head;
    }

    public function getDocTitle(): ?string
    {
        return $this->docTitle;
    }

    /**
     * @return NcxItemNavPoint[]|null
     */
    public function getNavPoints(): ?array
    {
        return $this->navPoints;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function toArray(): array
    {
        return [
            'head' => array_map(fn (NcxItemHead $item) => $item->toArray(), $this->head),
            'docTitle' => $this->docTitle,
            'navPoints' => array_map(fn (NcxItemNavPoint $item) => $item->toArray(), $this->navPoints),
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

class NcxItemHead
{
    protected function __construct(
        protected ?string $name = null,
        protected ?string $content = null,
    ) {}

    public static function make(array $xml): self
    {
        $self = new self;
        $self->name = $xml['name'] ?? null;
        $self->content = $xml['content'] ?? null;

        return $self;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContents(): ?string
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

class NcxItemNavPoint
{
    protected function __construct(
        protected ?string $id = null,
        protected ?int $playOrder = null,
        protected ?string $label = null,
        protected ?string $src = null,
        protected ?string $class = null,
    ) {}

    public static function make(array $xml): self
    {
        $self = new self;

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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPlayOrder(): ?int
    {
        return $this->playOrder;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getSrc(): ?string
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
