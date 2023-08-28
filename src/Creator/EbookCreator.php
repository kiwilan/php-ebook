<?php

namespace Kiwilan\Ebook\Creator;

use Kiwilan\Archive\Archive;
use Kiwilan\Archive\ArchiveZipCreate;

class EbookCreator
{
    public function __construct(
        protected string $path,
        protected ArchiveZipCreate $archive,
    ) {
    }

    public static function create(string $path): ArchiveZipCreate
    {
        $archive = Archive::make($path);
        $self = new self($path, $archive);

        return $self->archive;
    }
}
