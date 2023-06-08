<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Epub\EpubHtml;

it('can parse epub entity', function () {
    $ebook = Ebook::read(EPUB, true);
    $firstAuthor = $ebook->authors()[0];
    $basename = pathinfo(EPUB, PATHINFO_BASENAME);

    expect($ebook->path())->toBe(EPUB);
    expect($ebook->filename())->toBe($basename);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->title())->toBe('The Clan of the Cave Bear');
    expect($ebook->authorMain()->name())->toBe('Jean M. Auel');
    expect($ebook->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Jean M. Auel');
    expect($ebook->description())->toBeString();
    // expect($ebook->contributor())->toBeString();
    expect($ebook->copyright())->toBe('Copyright © 1980 by Jean M. Auel');
    expect($ebook->publisher())->toBe('Hodder & Stoughton');
    expect($ebook->identifiers())->toBeArray();
    expect($ebook->identifiers()['google']->content())->toBe('ASvHBAAAQBAJ');
    expect($ebook->identifiers()['isbn13']->content())->toBe('9780345529329');
    expect($ebook->publishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->publishDate()->format('Y-m-d H:i:s'))->toBe('1980-05-03 22:00:00');
    expect($ebook->language())->toBe('en');
    expect($ebook->tags())->toBeArray();
    expect($ebook->series())->toBe("Earth's Children");
    expect($ebook->volume())->toBe(1);
    // expect($ebook->rating())->toBeFloat();
    // expect($ebook->rating())->toBe(10.0);
    expect($ebook->pagesCount())->toBe(34);
    expect($ebook->wordsCount())->toBe(8267);

    $metadata = $ebook->metadata();
    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
    expect($metadata->__toString())->toBeString();
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

it('can read content', function () {
    $html = Ebook::read(EPUB)->metadata()?->html();

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
    $toc = $ebook->metadata()?->ncx();

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
    $chapters = $ebook->metadata()->chapters();

    expect($chapters)->toBeArray();
});

it('can parse with good performances', function () {
    $ebook = Ebook::read(EPUB);

    expect($ebook->metadata()->getExecTime())->toBeLessThan(0.01);

    $ebook = Ebook::read(EPUB, true);

    expect($ebook->metadata()->getExecTime())->toBeLessThan(0.05);
});
