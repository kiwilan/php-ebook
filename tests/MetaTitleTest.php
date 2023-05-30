<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Entity\MetaTitle;

it('can be slugify', function () {
    $ebook = Ebook::read(EPUB);

    $ebook->book()->setTitle('La pâle lumière des ténèbres');
    $ebook->book()->setVolume(1);
    $ebook->book()->setSeries('A comme Association');
    $ebook->book()->setLanguage('fr');
    $meta = MetaTitle::make($ebook);

    expect($meta->slug())->toBe('la-pale-lumiere-des-tenebres');
    expect($meta->slugSort())->toBe('pale-lumiere-des-tenebres');
    expect($meta->slugLang())->toBe('la-pale-lumiere-des-tenebres-epub-fr');
    expect($meta->serieSlug())->toBe('a-comme-association');
    expect($meta->serieSlugSort())->toBe('a-comme-association');
    expect($meta->serieSlugLang())->toBe('a-comme-association-epub-fr');
    expect($meta->slugSortWithSerie())->toBe('a-comme-association-01_pale-lumiere-des-tenebres');
});
