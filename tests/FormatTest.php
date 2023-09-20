<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;

it('can parse format', function (string $path) {
    $ebook = Ebook::read($path);
    $basename = pathinfo($path, PATHINFO_BASENAME);

    expect($ebook->getBasename())->toBe($basename);

    expect($ebook->getTitle())->toBe("Alice's Adventures in Wonderland");
    expect($ebook->getDescription())->toBe('With the curious, quick-witted Alice at its heart, readers will not only rediscover characters such as the charming White Rabbit, the formidable Queen of Hearts, the Mad Hatter and the grinning Cheshire Cat but will find fresh and wonderful creations of these characters by a true master of his art,; images that will live in our hearts and minds for generations to come.');
    expect($ebook->getDescriptionHtml())->toBeString();
    expect($ebook->getPublisher())->toBe('D. Appleton and Co');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getLanguage())->toBe('en');
    $isIsbn13 = str_starts_with($ebook->getIdentifiers()['isbn13']->getValue(), '978');
    expect($isIsbn13)->toBeTrue();

    $tags = $ebook->getTags();
    expect($tags)->toBeArray();

    $extension = pathinfo($ebook->getPath(), PATHINFO_EXTENSION);
    if (in_array($extension, ['azw3', 'epub', 'fb2', 'kf8', 'mobi'])) {
        $slug = $ebook->getMetaTitle()->getUniqueFilename();
        $path = "tests/output/{$slug}-cover.jpg";
        if (file_exists($path)) {
            unlink($path);
        }
        file_put_contents($path, $ebook->getCover()?->getContents());
        expect($ebook->getCover())->toBeInstanceOf(EbookCover::class);
        expect($path)->toBeReadableFile();
    }

    if (in_array($extension, ['epub', 'fb2'])) {
        expect($ebook->getSeries())->toBe('Alice Series');
        expect($ebook->getVolume())->toBe(1);
    }
})->with([
    FORMAT_AZW3,
    // FORMAT_DOCX,
    FORMAT_EPUB,
    FORMAT_FB2,
    // FORMAT_HTMLZ,
    FORMAT_KF8,
    // FORMAT_LIT,
    // FORMAT_LRF,
    FORMAT_MOBI,
    // FORMAT_PDB,
    // FORMAT_PDF,
    // FORMAT_PMLZ,
    FORMAT_PRC,
    // FORMAT_RB,
    // FORMAT_RTF,
    // FORMAT_SNB,
    // FORMAT_TCR,
    // FORMAT_TXT,
    // FORMAT_TXTZ,
    // FORMAT_ZIP,

    // FORMAT_DJVU,
]);

it('can parse mobi images', function () {
    recurseRmdir('tests/output/mobi');
    $parser = MobiParser::make(FORMAT_MOBI);

    expect($parser->getImages()->getItems())->toHaveCount(27);
    if (! is_dir('tests/output/mobi')) {
        mkdir('tests/output/mobi');
    }
    foreach ($parser->getImages()->getItems() as $key => $value) {
        $path = "tests/output/mobi/{$key}.jpg";
        file_put_contents($path, $value);
        expect($path)->toBeReadableFile();
    }

    if (! is_dir('tests/output/mobi')) {
        mkdir('tests/output/mobi');
    }

    $ebook = Ebook::read(FORMAT_MOBI);
    file_put_contents('tests/output/mobi/cover.jpg', $ebook->getCover()?->getContents());
    expect($ebook->getCover())->toBeInstanceOf(EbookCover::class);
    expect('tests/output/mobi/cover.jpg')->toBeReadableFile();
});
