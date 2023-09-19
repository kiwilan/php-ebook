<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\EbookCover;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiParser;
use Kiwilan\Ebook\Formats\Mobi\Parser\MobiReader;
use Kiwilan\Ebook\Formats\Mobi\RawParser;

// it('can create an instance of Ebook', function (string $path) {
//     $ebook = Ebook::read($path);
//     ray($ebook);

//     // expect($ebook)->toBeInstanceOf(Ebook::class);
//     // expect($ebook->getpath())->toBe($path);
//     // expect($ebook->toArray())->toBeArray();
//     // expect($ebook->toJson())->toBeString();
//     // expect($ebook->__toString())->toBeString();
// })->with([FORMAT_AZW3]);

it('can parse format', function (string $path) {
    // $raw = RawParser::make($path);
    $parser = MobiParser::make($path);
    $reader = MobiReader::make($parser);
    dump($reader);
    // dump($raw->getError());
    // $extension = pathinfo($path, PATHINFO_EXTENSION);
    // $basename = pathinfo($path, PATHINFO_BASENAME);
    // $ebook = Ebook::read($path);

    // ray($extension);
    // ray($ebook);

    // if ($extension === 'mobi') {
    //     ray($ebook);
    //     ray($ebook->getMetadata()->getModule());
    // }

    // expect($ebook->getBasename())->toBe($basename);

    // $slug = $ebook->getMetaTitle()->getUniqueFilename();
    // $path = "tests/output/{$slug}-cover.jpg";
    // if (file_exists($path)) {
    //     unlink($path);
    // }
    // file_put_contents($path, $ebook->getCover()?->getContent());
    // expect($ebook->getCover())->toBeInstanceOf(EbookCover::class);
    // expect($path)->toBeReadableFile();

    // expect($ebook->getTitle())->toBe("Alice's Adventures in Wonderland");
    // expect($ebook->getDescription())->toBe('With the curious, quick-witted Alice at its heart, readers will not only rediscover characters such as the charming White Rabbit, the formidable Queen of Hearts, the Mad Hatter and the grinning Cheshire Cat but will find fresh and wonderful creations of these characters by a true master of his art,; images that will live in our hearts and minds for generations to come.');
    // expect($ebook->getDescriptionHtml())->toBeString();
    // expect($ebook->getPublisher())->toBe('D. Appleton and Co');

    // $isIsbn13 = str_starts_with($ebook->getIdentifiers()['isbn13']->getValue(), '978');
    // expect($isIsbn13)->toBeTrue();
    // expect($ebook->getIdentifiers()['uuid']->getValue())->toBe('06a96cd8-27aa-4bdb-ac24-20b68456f43c');

    // $tags = $ebook->getTags();
    // expect($tags)->toBeArray();

    // expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    // expect($ebook->getFormat()->value)->toBe($extension);

    // expect($ebook->getLanguage())->toBe('en');
})->with([
    // FORMAT_EPUB,
    // FORMAT_AZW3,
    // FORMAT_FB2,
    // FORMAT_LRF,
    FORMAT_MOBI,
    // './tests/media/a-la-croisee-des-mondes.azw3',
    // FORMAT_PDB,
    // FORMAT_SNB,
    // FORMAT_RTF,
]);
