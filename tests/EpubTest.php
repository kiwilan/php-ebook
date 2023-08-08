<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Epub\EpubChapter;
use Kiwilan\Ebook\Formats\Epub\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\EpubHtml;
use Kiwilan\Ebook\Formats\Epub\NcxMetadata;
use Kiwilan\Ebook\Formats\Epub\OpfMetadata;

it('can parse epub entity', function () {
    $ebook = Ebook::read(EPUB);
    $firstAuthor = $ebook->getAuthors()[0];
    $basename = pathinfo(EPUB, PATHINFO_BASENAME);

    expect($ebook->getpath())->toBe(EPUB);
    expect($ebook->getFilename())->toBe($basename);
    expect($ebook->hasMetadata())->toBeTrue();

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBe('The Clan of the Cave Bear');
    expect($ebook->getAuthorMain()->getName())->toBe('Jean M. Auel');
    expect($ebook->getAuthors())->toBeArray();
    expect($firstAuthor->getName())->toBe('Jean M. Auel');
    expect($ebook->getDescription(1500))->toBeString();
    expect($ebook->getCopyright(255))->toBe('Copyright © 1980 by Jean M. Auel');
    expect($ebook->getCopyright(10))->toBe('Copyright…');
    expect($ebook->getPublisher())->toBe('Hodder & Stoughton');
    expect($ebook->getIdentifiers())->toBeArray();
    expect($ebook->getIdentifiers()['google']->getValue())->toBe('ASvHBAAAQBAJ');
    expect($ebook->getIdentifiers()['isbn13']->getValue())->toBe('9780345529329');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe('1980-05-03 22:00:00');
    expect($ebook->getLanguage())->toBe('en');
    expect($ebook->getTags())->toBeArray();
    expect($ebook->getSeries())->toBe("Earth's Children");
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getPagesCount())->toBe(34);
    expect($ebook->getWordsCount())->toBeGreaterThan(8000);

    expect($ebook->getExtras())->toBeArray();
    expect($ebook->getExtras()['contributor'])->toBeString();
    expect($ebook->getExtras()['rating'])->toBeFloat();
    expect($ebook->getExtras()['rating'])->toBe(10.0);
    expect($ebook->getExtra('contributor'))->toBeString();
    expect($ebook->getExtra('contributora'))->toBeNull();

    $metadata = $ebook->getMetadata();
    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
});

it('can get epub cover', function () {
    $ebook = Ebook::read(EPUB);
    $path = 'tests/output/cover-EPUB.jpg';
    file_put_contents($path, $ebook->getCover());

    expect($ebook->getCover()->getPath())->toBeString();
    expect($ebook->getCover()->getContent())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});

it('can get title meta', function () {
    $ebook = Ebook::read(EPUB);
    $meta = $ebook->getMetaTitle();

    expect($meta->getSlug())->toBe('the-clan-of-the-cave-bear');
    expect($meta->getSlugSort())->toBe('clan-of-the-cave-bear');
    expect($meta->getSlugLang())->toBe('the-clan-of-the-cave-bear-epub-en');
    expect($meta->getSerieSlug())->toBe('earths-children');
    expect($meta->getSerieSlugSort())->toBe('earths-children');
    expect($meta->getSerieSlugLang())->toBe('earths-children-epub-en');
    expect($meta->getSlugSortWithSerie())->toBe('earths-children-01_clan-of-the-cave-bear');

    expect($meta->toArray())->toBeArray();
    expect($meta->__toString())->toBeString();
});

it('can extract alt metadata', function () {
    $ebook = Ebook::read(EPUB_NO_META);

    expect($ebook->getTitle())->toBe('epub-no-meta');
});

it('can read epub metadata', function () {
    $epub = Ebook::read(EPUB)->getMetadata()?->getEpub();

    $container = $epub->getContainer();
    $opf = $epub->getOpf();
    $ncx = $epub->getNcx();
    $chapters = $epub->getChapters();
    $files = $epub->getFiles();
    $html = $epub->getHtml();
    $wordsCount = $epub->getWordsCount();
    $pagesCount = $epub->getPagesCount();

    expect($container)->toBeInstanceOf(EpubContainer::class);
    expect($opf)->toBeInstanceOf(OpfMetadata::class);
    expect($ncx)->toBeInstanceOf(NcxMetadata::class);
    expect($chapters)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubChapter::class));
    expect($files)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeString());
    expect($html)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubHtml::class));
    expect($wordsCount)->toBeInt();
    expect($pagesCount)->toBeInt();
});

it('can read content', function () {
    $html = Ebook::read(EPUB)->getMetadata()?->getEpub()?->getHtml();

    foreach ($html as $value) {
        expect($value)->toBeInstanceOf(EpubHtml::class);
        expect($value->getFilename())->toBeString();
        expect($value->getHead())->toBeString();
        expect($value->getBody())->toBeString();

        expect($value->toArray())->toBeArray();
        expect($value->toJson())->toBeString();
        expect($value->__toString())->toBeString();
    }
});

it('can read ncx', function () {
    $ebook = Ebook::read(EPUB);
    $toc = $ebook->getMetadata()?->getEpub()?->getNcx();

    if ($toc) {
        expect($toc->getHead())->toBeArray();
        expect($toc->getDocTitle())->toBeString();
        expect($toc->getNavPoints())->toBeArray();
        expect($toc->getVersion())->toBeString();
        expect($toc->getLang())->toBeString();
    } else {
        expect($toc)->toBeNull();
    }
});

it('can build EPUB render', function () {
    $ebook = Ebook::read(EPUB);
    $chapters = $ebook->getMetadata()->getEpub()->getChapters();

    expect($chapters)->toBeArray();
});

it('can parse with good performances', function () {
    $ebook = Ebook::read(EPUB);

    expect($ebook->getExecTime())->toBeLessThan(0.3);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse epub without tags', function () {
    $ebook = Ebook::read(EPUB_ONE_TAG);

    expect($ebook->getTags())->toBeArray();
    expect($ebook->getTags()[0])->toBeString();
});

it('can handle bad file', function () {
    expect(fn () => Ebook::read(EPUB_BAD_FILE))->toThrow(ValueError::class);
});

it('can handle bad epub', function (string $epub) {
    $ebook = Ebook::read($epub);

    expect($ebook->hasMetadata())->toBeFalse();
})->with([
    EPUB_NO_CONTAINER,
    EPUB_NO_OPF,
]);
