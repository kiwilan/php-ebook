<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Models\BookAuthor;
use Kiwilan\Ebook\Models\MetaTitle;

it('can be slugify', function () {
    $ebook = Ebook::read(EPUB);

    $ebook->setTitle('La pâle lumière des ténèbres');
    $ebook->setVolume(1);
    $ebook->setSeries('A comme Association');
    $ebook->setLanguage('fr');
    $ebook->setAuthorMain(new BookAuthor('Pierre Bottero'));
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('a-comme-association-fr-001-pale-lumiere-des-tenebres-pierre-bottero-1980-epub');
    expect($meta->getSlugSimple())->toBe('la-pale-lumiere-des-tenebres');
    expect($meta->getSeriesSlug())->toBe('a-comme-association-fr-pierre-bottero-epub');
    expect($meta->getSeriesSlugSimple())->toBe('a-comme-association');

    $ebook->setTitle('The Fellowship of the Ring');
    $ebook->setVolume(1);
    $ebook->setSeries('The Lord of the Rings');
    $ebook->setLanguage('en');
    $ebook->setAuthorMain(new BookAuthor('J. R. R. Tolkien'));
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('lord-of-the-rings-en-001-fellowship-of-the-ring-j-r-r-tolkien-1980-epub');
    expect($meta->getSlugSimple())->toBe('the-fellowship-of-the-ring');
    expect($meta->getSeriesSlug())->toBe('lord-of-the-rings-en-j-r-r-tolkien-epub');
    expect($meta->getSeriesSlugSimple())->toBe('the-lord-of-the-rings');

    $ebook->setTitle('Artemis');
    $ebook->setVolume(null);
    $ebook->setSeries(null);
    $ebook->setLanguage('en');
    $ebook->setAuthorMain(new BookAuthor('Andy Weir'));
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('artemis-en-andy-weir-1980-epub');
    expect($meta->getSlugSimple())->toBe('artemis');
    expect($meta->getSeriesSlug())->toBeNull();
    expect($meta->getSeriesSlugSimple())->toBeNull();

    $meta = MetaTitle::fromData(
        title: 'The Fellowship of the Ring',
        volume: 1,
        series: 'The Lord of the Rings',
        language: 'en',
        author: 'J. R. R. Tolkien',
        year: 1980,
        extension: 'epub',
    );

    expect($meta->getSlug())->toBe('lord-of-the-rings-en-001-fellowship-of-the-ring-j-r-r-tolkien-1980-epub');
    expect($meta->getSlugSimple())->toBe('the-fellowship-of-the-ring');
    expect($meta->getSeriesSlug())->toBe('lord-of-the-rings-en-j-r-r-tolkien-epub');
    expect($meta->getSeriesSlugSimple())->toBe('the-lord-of-the-rings');
});

it('can slug without intl', function () {
    $meta = MetaTitle::fromData(
        title: 'La pâle lumière des ténèbres',
        language: 'fr',
        series: 'A comme Association',
        volume: 1.5,
        author: 'Pierre Bottero',
        extension: 'epub',
        useIntl: false,
    );

    expect($meta->getSlug())->toBe('a-comme-association-fr-001.5-pale-lumiere-des-tenebres-pierre-bottero-epub');
    expect($meta->getSlugSimple())->toBe('la-pale-lumiere-des-tenebres');
    expect($meta->getSeriesSlug())->toBe('a-comme-association-fr-pierre-bottero-epub');
    expect($meta->getSeriesSlugSimple())->toBe('a-comme-association');
});

it('can use alt volume', function () {
    $ebook = Ebook::read(EPUB_VOLFLOAT);
    $meta = $ebook->getMetaTitle();

    expect($meta->getSlug())->toBe('enfants-de-la-terre-fr-001.5-clan-de-lours-des-cavernes-jean-m-auel-1980-epub');
});

it('can use determiner title', function () {
    $ebook = Ebook::read(EPUB);
    $ebook->setSeries("L'Assassin Royal");
    $ebook->setLanguage('fr');
    $ebook->setAuthorMain(new BookAuthor('Robin Hobb'));

    $ebook->setTitle("L'apprenti assassin");
    $ebook->setVolume(1);
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('assassin-royal-fr-001-apprenti-assassin-robin-hobb-1980-epub');

    $ebook->setTitle('Le Prince Bâtard');
    $ebook->setVolume(0);
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('assassin-royal-fr-000-prince-batard-robin-hobb-1980-epub');
    ray($ebook->toArray());

    $ebook->setTitle("L'apprenti assassin");
    $ebook->setVolume(50);
    $meta = MetaTitle::fromEbook($ebook);

    expect($meta->getSlug())->toBe('assassin-royal-fr-050-apprenti-assassin-robin-hobb-1980-epub');
});
