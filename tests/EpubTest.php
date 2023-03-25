<?php

it('can parse epub entity', function () {
    $entity = Kiwilan\Ebook\Ebook::make(EPUB)->entity();
    $firstAuthor = $entity->authors()[0];

    expect($entity)->toBeInstanceOf(Kiwilan\Ebook\EbookEntity::class);
    expect($entity->path())->toBe(EPUB);
    expect($entity->title())->toBe("Le clan de l'ours des cavernes");
    expect($entity->authors())->toBeArray();
    expect($firstAuthor->name())->toBe('Jean M. Auel');
    expect($entity->description())->toBeString();
    expect($entity->contributor())->toBeString();
    expect($entity->rights())->toBeNull();
    expect($entity->publisher())->toBe('Presses de la cité');
    expect($entity->identifiers())->toBeArray();
    expect($entity->identifierGoogle())->toBe('63CTHAAACAAJ');
    expect($entity->identifierIsbn13())->toBe('9782266122122');
    expect($entity->date())->toBeInstanceOf(DateTime::class);
    expect($entity->date()->format('Y-m-d H:i:s'))->toBe('1980-01-13 21:00:00');
    expect($entity->language())->toBe('fr');
    expect($entity->tags())->toBeArray();
    expect($entity->series())->toBe('Les Enfants de la Terre');
    expect($entity->volume())->toBe(1);
    expect($entity->rating())->toBe(10);
    expect($entity->pageCount())->toBe(4);
});

it('can get epub cover', function () {
    $entity = Kiwilan\Ebook\Ebook::make(EPUB)->entity();
    $path = 'tests/output/cover.jpg';
    file_put_contents($path, $entity->cover());

    expect($entity->cover())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});
