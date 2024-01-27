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

    expect($meta->getSlug())->toBe('pale-lumiere-des-tenebres-a-comme-association-01-pierre-bottero-1980-epub-fr');
    expect($meta->getSlugSimple())->toBe('la-pale-lumiere-des-tenebres');

    expect($meta->getSeriesSlug())->toBe('a-comme-association-pierre-bottero-1980-epub-fr');
    expect($meta->getSeriesSlugSimple())->toBe('a-comme-association');
});
