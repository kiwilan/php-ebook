<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

use Kiwilan\Ebook\Utils\Stream;

class MobiImages
{
    protected function __construct(
        protected Stream $stream,
        protected array $items = [],
    ) {}

    public static function make(string $path): ?self
    {
        $self = new self(Stream::make($path));

        $fileContent = $self->stream->read($self->stream->filesize());
        $self->stream->close();

        $regexJpg = '/\xff\xd8\xff.*?\xff\xd9/s';
        if (preg_match_all($regexJpg, $fileContent, $matches)) {
            foreach ($matches[0] as $index => $imageData) {
                $self->items[] = base64_encode($imageData);
            }
        }

        return $self;
    }

    /**
     * @return string[]
     */
    public function getItems(bool $base64Decode = true): array
    {
        if (! $base64Decode) {
            return $this->items;
        }

        $data = [];
        foreach ($this->items as $item) {
            $data[] = base64_decode($item);
        }

        return $data;
    }
}
