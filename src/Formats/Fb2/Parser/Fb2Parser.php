<?php

namespace Kiwilan\Ebook\Formats\Fb2\Parser;

use Kiwilan\XmlReader\XmlReader;

class Fb2Parser
{
    /**
     * @param  Fb2MetaBinaryItem[]  $binary
     */
    protected function __construct(
        protected string $path,
        protected XmlReader $xml,
        protected ?string $root = null,
        protected array $contents = [],
        protected ?Fb2MetaDescription $description = null,
        protected ?array $body = null,
        protected ?array $binary = null,
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
        $self->contents = $xml->getContent();
        $self->buildContainer();

        return $self;
    }

    /**
     * @return array {titleInfo: array, documentInfo: array, publishInfo: array}
     */
    private function parseMetadata(): array
    {
        if (! array_key_exists('description', $this->contents)) {
            return [
                'titleInfo' => null,
                'documentInfo' => null,
                'publishInfo' => null,
            ];
        }

        $metadata = $this->contents['description'];
        $titleInfo = null;
        $documentInfo = null;
        $publishInfo = null;

        if (array_key_exists('title-info', $metadata)) {
            $titleInfo = $metadata['title-info'];
        }

        if (array_key_exists('document-info', $metadata)) {
            $documentInfo = $metadata['document-info'];
        }

        if (array_key_exists('publish-info', $metadata)) {
            $publishInfo = $metadata['publish-info'];
        }

        return [
            'titleInfo' => $titleInfo,
            'documentInfo' => $documentInfo,
            'publishInfo' => $publishInfo,
        ];
    }

    private function buildContainer(): self
    {
        $metadata = $this->parseMetadata();
        $titleInfo = $metadata['titleInfo'];
        $documentInfo = $metadata['documentInfo'];
        $publishInfo = $metadata['publishInfo'];

        $this->description = new Fb2MetaDescription(
            title: $this->buildTitle($titleInfo),
            document: $this->buildDocument($documentInfo),
            publish: $this->buildPublish($publishInfo),
        );
        $this->body = $this->contents['body'] ?? [];
        $this->binary = $this->buildBinaryItems($this->contents['binary'] ?? []);

        $this->cover = $this->findCover();

        return $this;
    }

    private function buildTitle(mixed $titleInfo): ?Fb2MetaDescriptionTitle
    {
        if (! is_array($titleInfo)) {
            return null;
        }

        $coverpage = $titleInfo['coverpage'] ?? null;
        if (is_array($coverpage)) {
            $coverpage = new Fb2MetaCoverpage(
                href: $coverpage['image']['@attributes']['href'] ?? null,
            );
        } else {
            $coverpage = null;
        }

        $sequence = $titleInfo['sequence'] ?? null;
        if (is_array($sequence)) {
            $sequence = new Fb2MetaSequence(
                name: $sequence['@attributes']['name'] ?? null,
                number: $sequence['@attributes']['number'] ?? null,
            );
        } else {
            $sequence = null;
        }

        $annotation = $titleInfo['annotation'] ?? null;

        return new Fb2MetaDescriptionTitle(
            genre: $titleInfo['genre'] ?? null,
            author: $this->parseAuthor($titleInfo['author'] ?? null),
            bookTitle: $titleInfo['book-title'] ?? null,
            coverpage: $coverpage,
            lang: $titleInfo['lang'] ?? null,
            keywords: $titleInfo['keywords'] ?? null,
            sequence: $sequence,
            annotation: $annotation,
        );
    }

    private function buildDocument(mixed $documentInfo): ?Fb2MetaDescriptionDocument
    {
        if (! is_array($documentInfo)) {
            return null;
        }

        return new Fb2MetaDescriptionDocument(
            author: $this->parseAuthor($documentInfo['author'] ?? null),
            programUsed: $documentInfo['program-used'] ?? null,
            date: $documentInfo['date'] ?? null,
            id: $documentInfo['id'] ?? null,
            version: $documentInfo['version'] ?? null,
        );
    }

    private function buildPublish(mixed $publishInfo): ?Fb2MetaDescriptionPublish
    {
        if (! is_array($publishInfo)) {
            return null;
        }

        return new Fb2MetaDescriptionPublish(
            publisher: $publishInfo['publisher'] ?? null,
            year: $publishInfo['year'] ?? null,
            isbn: $publishInfo['isbn'] ?? null,
        );
    }

    /**
     * @return Fb2MetaAuthor[]
     */
    private function parseAuthor(mixed $author): array
    {
        if (! is_array($author)) {
            $author = [$author];
        }

        $authors = [];
        foreach ($author as $value) {
            if (! is_array($value)) {
                continue;
            }

            $firstName = $value['first-name'] ?? null;
            $lastName = $value['last-name'] ?? null;

            $authors[] = new Fb2MetaAuthor(
                firstName: $firstName,
                lastName: $lastName,
            );
        }

        return $authors;
    }

    /**
     * @return Fb2MetaBinaryItem[]
     */
    private function buildBinaryItems(array $binary): array
    {
        if (! is_array($binary)) {
            return [];
        }

        $items = [];
        foreach ($binary as $value) {
            $attributes = $value['@attributes'] ?? null;
            $content = $value['@content'] ?? null;

            $items[] = new Fb2MetaBinaryItem(
                attributes: $attributes,
                content: $content,
            );
        }

        return $items;
    }

    private function findCover(): ?string
    {
        if (! is_array($this->contents['binary'])) {
            return null;
        }

        $href = $this->description?->title?->coverpage?->href ?? null;
        if (! $href) {
            return null;
        } else {
            $href = str_replace('#', '', $href);
        }

        foreach ($this->contents['binary'] as $item) {
            $attributes = $item['@attributes'] ?? null;
            $binaryId = $attributes['id'] ?? null;
            if ($binaryId === $href) {
                $cover = $item['@content'] ?? null;
                $cover = str_replace("\n", '', $cover);

                return $cover;
            }
        }

        return null;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function getContents(): array
    {
        return $this->contents;
    }

    public function getDescription(): ?Fb2MetaDescription
    {
        return $this->description;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * @return Fb2MetaBinaryItem[]
     */
    public function getBinary(): ?array
    {
        return $this->binary;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }
}

class Fb2MetaDescription
{
    public function __construct(
        public ?Fb2MetaDescriptionTitle $title = null,
        public ?Fb2MetaDescriptionDocument $document = null,
        public ?Fb2MetaDescriptionPublish $publish = null,
    ) {
    }
}

class Fb2MetaDescriptionTitle
{
    /**
     * @param  Fb2MetaAuthor[]  $author
     */
    public function __construct(
        public ?string $genre = null,
        public ?array $author = null,
        public ?string $bookTitle = null,
        public ?Fb2MetaCoverpage $coverpage = null,
        public ?string $lang = null,
        public ?string $keywords = null,
        public ?Fb2MetaSequence $sequence = null,
        public ?array $annotation = null,
    ) {
    }
}

class Fb2MetaDescriptionDocument
{
    /**
     * @param  Fb2MetaAuthor[]  $author
     */
    public function __construct(
        public ?array $author = null,
        public ?string $programUsed = null,
        public ?string $date = null,
        public ?string $id = null,
        public ?string $version = null,
    ) {
    }
}

class Fb2MetaDescriptionPublish
{
    public function __construct(
        public ?string $publisher = null,
        public ?string $year = null,
        public ?string $isbn = null,
    ) {
    }
}

class Fb2MetaBinaryItem
{
    public function __construct(
        public ?array $attributes = null,
        public ?string $content = null,
    ) {
    }
}

class Fb2MetaAuthor
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
    ) {
    }
}

class Fb2MetaSequence
{
    public function __construct(
        public ?string $name = null,
        public ?string $number = null,
    ) {
    }
}

class Fb2MetaCoverpage
{
    public function __construct(
        public ?string $href = null,
    ) {
    }
}
