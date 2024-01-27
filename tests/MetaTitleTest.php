<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\MetaTitle;

it('can be slugify', function () {
    $ebook = Ebook::read(EPUB);

    $ebook->setTitle('La pâle lumière des ténèbres');
    $ebook->setVolume(1);
    $ebook->setSeries('A comme Association');
    $ebook->setLanguage('fr');
    $ebook->setAuthorMain(new BookAuthor('Pierre Bottero'));
    $meta = MetaTitle::make($ebook);

    expect($meta->getSlug())->toBe('a-comme-association-01-pale-lumiere-des-tenebres-pierre-bottero-1980-epub-fr');
    expect($meta->getSlugSimple())->toBe('la-pale-lumiere-des-tenebres');
    expect($meta->getSeriesSlug())->toBe('a-comme-association-pierre-bottero-1980-epub-fr');
    expect($meta->getSeriesSlugSimple())->toBe('a-comme-association');

    $ebook->setTitle('The Fellowship of the Ring');
    $ebook->setVolume(1);
    $ebook->setSeries('The Lord of the Rings');
    $ebook->setLanguage('en');
    $ebook->setAuthorMain(new BookAuthor('J. R. R. Tolkien'));
    $meta = MetaTitle::make($ebook);

    expect($meta->getSlug())->toBe('lord-of-the-rings-01-fellowship-of-the-ring-j-r-r-tolkien-1980-epub-en');
    expect($meta->getSlugSimple())->toBe('the-fellowship-of-the-ring');
    expect($meta->getSeriesSlug())->toBe('lord-of-the-rings-j-r-r-tolkien-1980-epub-en');
    expect($meta->getSeriesSlugSimple())->toBe('the-lord-of-the-rings');

    $ebook->setTitle('Artemis');
    $ebook->setVolume(null);
    $ebook->setSeries(null);
    $ebook->setLanguage('en');
    $ebook->setAuthorMain(new BookAuthor('Andy Weir'));
    $meta = MetaTitle::make($ebook);

    expect($meta->getSlug())->toBe('artemis-andy-weir-1980-epub-en');
    expect($meta->getSlugSimple())->toBe('artemis');
    expect($meta->getSeriesSlug())->toBeNull();
    expect($meta->getSeriesSlugSimple())->toBeNull();
});
