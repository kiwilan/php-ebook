<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Tools\BookAuthor;
use Kiwilan\Ebook\Tools\BookContributor;
use Kiwilan\Ebook\Tools\BookIdentifier;
use Kiwilan\Ebook\Tools\BookMeta;

it('can use EbookEntity', function () {
    $ebook = Ebook::read(EPUB);
    $ebook->setTitle('title');
    $ebook->setAuthors([
        new BookAuthor('author', 'aut'),
    ]);
    $ebook->setDescription('description');
    $extras = [
        'contributor' => 'contributor',
        'rights' => 'rights',
        'rating' => 10.0,
    ];
    $ebook->setPublisher('publisher');
    $ebook->setIdentifiers([
        new BookIdentifier('identifier', 'id'),
    ]);
    $ebook->setPublishDate(new DateTime('1980-01-13 21:00:00'));
    $ebook->setLanguage('fr');
    $ebook->setTags([
        'tag',
    ]);
    $ebook->setIdentifiers([
        'amazon' => new BookIdentifier('identifierAmazon', 'amazon'),
        'google' => new BookIdentifier('identifierGoogle', 'google'),
        'isbn10' => new BookIdentifier('identifierIsbn10', 'isbn10'),
        'isbn13' => new BookIdentifier('identifierIsbn13', 'isbn13'),
    ]);
    $ebook->setSeries('series');
    $ebook->setVolume(1);
    $ebook->setPagesCount(4);
    $ebook->setExtras($extras);

    expect($ebook->title())->toBe('title');
    expect($ebook->authors())->toBeArray();
    expect($ebook->description())->toBe('description');
    expect($ebook->extras())->toBe($extras);
    expect($ebook->publisher())->toBe('publisher');
    expect($ebook->identifiers())->toBeArray();
    expect($ebook->publishDate()->format('Y-m-d H:i:s'))->toBe('1980-01-13 21:00:00');
    expect($ebook->language())->toBe('fr');
    expect($ebook->tags())->toBe([
        'tag',
    ]);
    expect($ebook->identifiers()['amazon']->content())->toBe('identifierAmazon');
    expect($ebook->identifiers()['google']->content())->toBe('identifierGoogle');
    expect($ebook->identifiers()['isbn10']->content())->toBe('identifierIsbn10');
    expect($ebook->identifiers()['isbn13']->content())->toBe('identifierIsbn13');
    expect($ebook->series())->toBe('series');
    expect($ebook->volume())->toBe(1);
    expect($ebook->pagesCount())->toBe(4);

    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();
});

it('can use BookContributor', function (string $content, string $role) {
    $item = new BookContributor($content, $role);

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

it('can use BookAuthor', function (string $name, string $role) {
    $item = new BookAuthor($name, $role);

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

it('can use BookIdentifier', function (string $content, string $type) {
    $item = new BookIdentifier($content, $type);

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

it('can use BookMeta', function (string $name, string $content) {
    $item = new BookMeta($name, $content);

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
