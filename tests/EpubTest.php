<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Epub\EpubChapter;
use Kiwilan\Ebook\Formats\Epub\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\EpubHtml;
use Kiwilan\Ebook\Formats\Epub\NcxMetadata;
use Kiwilan\Ebook\Formats\Epub\OpfMetadata;

it('can parse epub entity', function () {
    $ebook = Ebook::read(EPUB);
    $firstAuthor = $ebook->authors()[0];
    $basename = pathinfo(EPUB, PATHINFO_BASENAME);

    expect($ebook->path())->toBe(EPUB);
    expect($ebook->filename())->toBe($basename);
    expect($ebook->hasMetadata())->toBeTrue();

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->title())->toBe('The Clan of the Cave Bear');
    expect($ebook->authorMain()->name())->toBe('Jean M. Auel');
    expect($ebook->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Jean M. Auel');
    expect($ebook->description())->toBeString();
    expect($ebook->copyright())->toBe('Copyright © 1980 by Jean M. Auel');
    expect($ebook->publisher())->toBe('Hodder & Stoughton');
    expect($ebook->identifiers())->toBeArray();
    expect($ebook->identifiers()['google']->value())->toBe('ASvHBAAAQBAJ');
    expect($ebook->identifiers()['isbn13']->value())->toBe('9780345529329');
    expect($ebook->publishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->publishDate()->format('Y-m-d H:i:s'))->toBe('1980-05-03 22:00:00');
    expect($ebook->language())->toBe('en');
    expect($ebook->tags())->toBeArray();
    expect($ebook->series())->toBe("Earth's Children");
    expect($ebook->volume())->toBe(1);
    expect($ebook->pagesCount())->toBe(34);
    expect($ebook->wordsCount())->toBe(8267);

    expect($ebook->extras())->toBeArray();
    expect($ebook->extras()['contributor'])->toBeString();
    expect($ebook->extras()['rating'])->toBeFloat();
    expect($ebook->extras()['rating'])->toBe(10.0);
    expect($ebook->extrasExtract('contributor'))->toBeString();
    expect($ebook->extrasExtract('contributora'))->toBeNull();

    $metadata = $ebook->metadata();
    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
});

it('can get epub cover', function () {
    $ebook = Ebook::read(EPUB);
    $path = 'tests/output/cover-EPUB.jpg';
    file_put_contents($path, $ebook->cover());

    expect($ebook->cover()->path())->toBeString();
    expect($ebook->cover()->content())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});

it('can get title meta', function () {
    $ebook = Ebook::read(EPUB);
    $meta = $ebook->metaTitle();

    expect($meta->slug())->toBe('the-clan-of-the-cave-bear');
    expect($meta->slugSort())->toBe('clan-of-the-cave-bear');
    expect($meta->slugLang())->toBe('the-clan-of-the-cave-bear-epub-en');
    expect($meta->serieSlug())->toBe('earths-children');
    expect($meta->serieSlugSort())->toBe('earths-children');
    expect($meta->serieSlugLang())->toBe('earths-children-epub-en');
    expect($meta->slugSortWithSerie())->toBe('earths-children-01_clan-of-the-cave-bear');

    expect($meta->toArray())->toBeArray();
    expect($meta->__toString())->toBeString();
});

it('can extract alt metadata', function () {
    $ebook = Ebook::read(EPUB_NO_META);

    expect($ebook->title())->toBe('epub-no-meta');
});

it('can read epub metadata', function () {
    $epub = Ebook::read(EPUB)->metadata()?->epub();

    $container = $epub->container();
    $opf = $epub->opf();
    $ncx = $epub->ncx();
    $chapters = $epub->chapters();
    $files = $epub->files();
    $html = $epub->html();
    $wordsCount = $epub->wordsCount();
    $pagesCount = $epub->pagesCount();

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
    $html = Ebook::read(EPUB)->metadata()?->epub()?->html();

    foreach ($html as $value) {
        expect($value)->toBeInstanceOf(EpubHtml::class);
        expect($value->filename())->toBeString();
        expect($value->head())->toBeString();
        expect($value->body())->toBeString();

        expect($value->toArray())->toBeArray();
        expect($value->toJson())->toBeString();
        expect($value->__toString())->toBeString();
    }
});

it('can read ncx', function () {
    $ebook = Ebook::read(EPUB);
    $toc = $ebook->metadata()?->epub()?->ncx();

    if ($toc) {
        expect($toc->head())->toBeArray();
        expect($toc->docTitle())->toBeString();
        expect($toc->navPoints())->toBeArray();
        expect($toc->version())->toBeString();
        expect($toc->lang())->toBeString();
    } else {
        expect($toc)->toBeNull();
    }
});

it('can build EPUB render', function () {
    $ebook = Ebook::read(EPUB);
    $chapters = $ebook->metadata()->epub()->chapters();

    expect($chapters)->toBeArray();
});

it('can parse with good performances', function () {
    $ebook = Ebook::read(EPUB);

    expect($ebook->execTime())->toBeLessThan(0.06);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse epub without tags', function () {
    $ebook = Ebook::read(EPUB_ONE_TAG);

    expect($ebook->tags())->toBeArray();
    expect($ebook->tags()[0])->toBeString();
});
