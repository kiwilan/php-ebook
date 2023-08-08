<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Tools\MetaTitle;

it('can be slugify', function () {
    $ebook = Ebook::read(EPUB);

    $ebook->setTitle('La pâle lumière des ténèbres');
    $ebook->setVolume(1);
    $ebook->setSeries('A comme Association');
    $ebook->setLanguage('fr');
    $meta = MetaTitle::make($ebook);

    expect($meta->getSlug())->toBe('la-pale-lumiere-des-tenebres');
    expect($meta->getSlugSort())->toBe('pale-lumiere-des-tenebres');
    expect($meta->getSlugLang())->toBe('la-pale-lumiere-des-tenebres-epub-fr');
    expect($meta->getSerieSlug())->toBe('a-comme-association');
    expect($meta->getSerieSlugSort())->toBe('a-comme-association');
    expect($meta->getSerieSlugLang())->toBe('a-comme-association-epub-fr');
    expect($meta->getSlugSortWithSerie())->toBe('a-comme-association-01_pale-lumiere-des-tenebres');
    expect($meta->getUniqueFilename())->toBe('fr-a-comme-association-01-la-pale-lumiere-des-tenebres-jean-m-auel-epub');
});
