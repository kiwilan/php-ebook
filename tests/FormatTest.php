<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;

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

    if ($path === FORMAT_FB2 || $path === FORMAT_EPUB) {
        $slug = $ebook->getMetaTitle()->getUniqueFilename();
        $path = "tests/output/{$slug}-cover.jpg";
        if (file_exists($path)) {
            unlink($path);
        }
        file_put_contents($path, $ebook->getCover()?->getContent());
        expect($ebook->getCover())->toBeInstanceOf(EbookCover::class);
        expect($path)->toBeReadableFile();
    }
})->with([
    FORMAT_AZW3,
    FORMAT_EPUB,
    FORMAT_FB2,
    FORMAT_KF8,
    // FORMAT_LRF,
    FORMAT_MOBI,
    // FORMAT_PDB,
    // FORMAT_PRC,
    // FORMAT_RB,
    // FORMAT_SNB,

    // FORMAT_DJVU,
    // FORMAT_RTF,
]);
