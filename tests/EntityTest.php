<?php

use Kiwilan\Ebook\EbookEntity;
use Kiwilan\Ebook\Entity\EntityContributor;
use Kiwilan\Ebook\Entity\EntityCreator;
use Kiwilan\Ebook\Entity\EntityIdentifier;
use Kiwilan\Ebook\Entity\EntityMeta;

it('can use EbookEntity', function () {
    $item = EbookEntity::make('path/to/book.epub');
    $item->setTitle('title');
    $item->setAuthors([
        new EntityCreator('author', 'aut'),
    ]);
    $item->setDescription('description');
    $item->setContributor('contributor');
    $item->setRights('rights');
    $item->setPublisher('publisher');
    $item->setIdentifiers([
        new EntityIdentifier('identifier', 'id'),
    ]);
    $item->setDate(new DateTime('1980-01-13 21:00:00'));
    $item->setLanguage('fr');
    $item->setTags([
        'tag',
    ]);
    $item->setIdentifierAmazon('identifierAmazon');
    $item->setIdentifierGoogle('identifierGoogle');
    $item->setIdentifierIsbn10('identifierIsbn10');
    $item->setIdentifierIsbn13('identifierIsbn13');
    $item->setSeries('series');
    $item->setVolume(1);
    $item->setRating(10);
    $item->setPageCount(4);
    $item->setCover('cover');

    expect($item->path())->toBe('path/to/book.epub');
    expect($item->title())->toBe('title');
    expect($item->authors())->toBeArray();
    expect($item->description())->toBe('description');
    expect($item->contributor())->toBe('contributor');
    expect($item->rights())->toBe('rights');
    expect($item->publisher())->toBe('publisher');
    expect($item->identifiers())->toBeArray();
    expect($item->date()->format('Y-m-d H:i:s'))->toBe('1980-01-13 21:00:00');
    expect($item->language())->toBe('fr');
    expect($item->tags())->toBe([
        'tag',
    ]);
    expect($item->identifierAmazon())->toBe('identifierAmazon');
    expect($item->identifierGoogle())->toBe('identifierGoogle');
    expect($item->identifierIsbn10())->toBe('identifierIsbn10');
    expect($item->identifierIsbn13())->toBe('identifierIsbn13');
    expect($item->series())->toBe('series');
    expect($item->volume())->toBe(1);
    expect($item->rating())->toBe(10);
    expect($item->pageCount())->toBe(4);
    expect($item->cover())->toBe('cover');
});

it('can use EntityContributor', function (string $content, string $role) {
    $item = new EntityContributor($content, $role);

    expect($item->content())->toBe($content);
    expect($item->role())->toBe($role);
    expect($item->toArray())->toBe([
        'content' => $content,
        'role' => $role,
    ]);
    expect($item->__toString())->toBe($content);
})->with([
    [
        'content' => 'calibre',
        'role' => 'bkp',
    ],
    [
        'content' => 'epbu2',
        'role' => 'bkp',
    ],
]);

it('can use EntityCreator', function (string $name, string $role) {
    $item = new EntityCreator($name, $role);

    expect($item->name())->toBe($name);
    expect($item->role())->toBe($role);
    expect($item->toArray())->toBe([
        'name' => $name,
        'role' => $role,
    ]);
    expect($item->__toString())->toBe($name);
})->with([
    [
        'content' => 'Jean M. Auel',
        'role' => 'aut',
    ],
    [
        'content' => 'Terry Pratchett',
        'role' => 'aut',
    ],
]);

it('can use EntityIdentifier', function (string $content, string $type) {
    $item = new EntityIdentifier($content, $type);

    expect($item->content())->toBe($content);
    expect($item->type())->toBe($type);
    expect($item->toArray())->toBe([
        'content' => $content,
        'type' => $type,
    ]);
    expect($item->__toString())->toBe("{$content} {$type}");
    if (str_contains($type, 'isbn')) {
        expect($item->parse()->type())->toBe($type);
    }
})->with([
    [
        'content' => 'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'type' => 'uuid',
    ],
    [
        'content' => 'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'type' => 'calibre',
    ],
    [
        'content' => '63CTHAAACAAJ',
        'type' => 'google',
    ],
    [
        'content' => '2744155349',
        'type' => 'isbn10',
    ],
    [
        'content' => '9782266122122',
        'type' => 'isbn13',
    ],
]);

it('can use EntityMeta', function (string $name, string $content) {
    $item = new EntityMeta($name, $content);

    expect($item->name())->toBe($name);
    expect($item->content())->toBe($content);
    expect($item->toArray())->toBe([
        'name' => $name,
        'content' => $content,
    ]);
    expect($item->__toString())->toBe("{$name} {$content}");
})->with([
    [
        'name' => 'calibre:title_sort',
        'content' => "clan de l'ours des cavernes, Le",
    ],
    [
        'name' => 'calibre:series',
        'content' => 'Les Enfants de la Terre',
    ],
    [
        'name' => 'calibre:series_index',
        'content' => '1.0',
    ],
    [
        'name' => 'calibre:timestamp',
        'content' => '2023-03-25T10:32:21+00:00',
    ],
    [
        'name' => 'calibre:rating',
        'content' => '10.0',
    ],
]);
