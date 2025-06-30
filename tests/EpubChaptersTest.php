<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubChapter;

it('can parse epub chapters', function () {
    $ebook = Ebook::read(EPUB);
    $chapters = $ebook->getParser()->getEpub()->getChapters();

    expect($chapters)->toBeArray();
    expect($chapters)->toHaveCount(41);
    expect($chapters[0])->toBeInstanceOf(EpubChapter::class);
    expect($chapters[0]->getLabel())->toBe('Cover');
    expect($chapters[0]->getSource())->toBe('titlepage.xhtml');
    expect($chapters[0]->getContent())->toBeString();
});
