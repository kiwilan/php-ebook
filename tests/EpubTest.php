<?php

use Kiwilan\Ebook\Ebook;

it('can parse epub entity', function () {
    $ebook = Ebook::read(EPUB);
    $book = $ebook->book();
    $firstAuthor = $book->authors()[0];
    $basename = pathinfo(EPUB, PATHINFO_BASENAME);

    expect($ebook->path())->toBe(EPUB);
    expect($ebook->filename())->toBe($basename);

    expect($book)->toBeInstanceOf(Kiwilan\Ebook\BookEntity::class);
    expect($book->title())->toBe('The Clan of the Cave Bear');
    expect($book->authorFirst()->name())->toBe('Jean M. Auel');
    expect($book->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Jean M. Auel');
    expect($book->description())->toBeString();
    expect($book->contributor())->toBeString();
    expect($book->rights())->toBe('Copyright © 1980 by Jean M. Auel');
    expect($book->publisher())->toBe('Hodder & Stoughton');
    expect($book->identifiers())->toBeArray();
    expect($book->identifiers()['google']->content())->toBe('ASvHBAAAQBAJ');
    expect($book->identifiers()['isbn13']->content())->toBe('9780345529329');
    expect($book->date())->toBeInstanceOf(DateTime::class);
    expect($book->date()->format('Y-m-d H:i:s'))->toBe('1980-05-03 22:00:00');
    expect($book->language())->toBe('en');
    expect($book->tags())->toBeArray();
    expect($book->series())->toBe("Earth's Children");
    expect($book->volume())->toBe(1);
    expect($book->rating())->toBeFloat();
    expect($book->rating())->toBe(10.0);
    expect($book->pageCount())->toBe(35);
    expect($book->wordsCount())->toBe(8596);

    $metadata = $ebook->metadata();
    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
    expect($metadata->__toString())->toBeString();
});

it('can get epub cover', function () {
    $ebook = Kiwilan\Ebook\Ebook::read(EPUB);
    $path = 'tests/output/cover-EPUB.jpg';
    file_put_contents($path, $ebook->cover());

    expect($ebook->cover())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});

it('can get title meta', function () {
    $book = Kiwilan\Ebook\Ebook::read(EPUB)->book();
    $meta = $book->metaTitle();

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

    expect($ebook->book()->title())->toBe('epub-no-meta');
});

it('can read content', function () {
    $html = Ebook::read(EPUB)->metadata()->html();

    foreach ($html as $value) {
        expect($value)->toBeInstanceOf(Kiwilan\Ebook\Epub\EpubHtml::class);
        expect($value->filename())->toBeString();
        expect($value->head())->toBeString();
        expect($value->body())->toBeString();

        expect($value->toArray())->toBeArray();
        expect($value->toJson())->toBeString();
        expect($value->__toString())->toBeString();
    }
});

it('can read TOC', function () {
    $ebook = Ebook::read(EPUB);
    $toc = $ebook->metadata()->toc();

    dump($toc->navPoints());

    expect($toc->head())->toBeArray();
    expect($toc->docTitle())->toBeString();
    expect($toc->navPoints())->toBeArray();
    expect($toc->version())->toBeString();
    expect($toc->lang())->toBeString();
});

it('can parse with good performances', function () {
    $time_pre = microtime(true);
    Ebook::read(EPUB);
    $time_post = microtime(true);
    $exec_time = $time_post - $time_pre;

    $time = number_format((float) $exec_time, 5, '.', '');

    expect($time)->toBeLessThan(0.1);
});
