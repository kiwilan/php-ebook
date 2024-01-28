<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Models\BookAuthor;
use Kiwilan\Ebook\Models\BookContributor;
use Kiwilan\Ebook\Models\BookIdentifier;
use Kiwilan\Ebook\Models\BookMeta;

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
    $ebook->setIdentifier(new BookIdentifier('identifier', 'id'));
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

    expect($ebook->getTitle())->toBe('title');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getDescription())->toBe('description');
    expect($ebook->getExtras())->toBe($extras);
    expect($ebook->getPublisher())->toBe('publisher');
    expect($ebook->getIdentifiers())->toBeArray();
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe('1980-01-13 21:00:00');
    expect($ebook->getLanguage())->toBe('fr');
    expect($ebook->getTags())->toBe([
        'tag',
    ]);
    expect($ebook->getIdentifiers()['amazon']->getValue())->toBe('identifierAmazon');
    expect($ebook->getIdentifiers()['google']->getValue())->toBe('identifierGoogle');
    expect($ebook->getIdentifiers()['isbn10']->getValue())->toBe('identifierIsbn10');
    expect($ebook->getIdentifiers()['isbn13']->getValue())->toBe('identifierIsbn13');
    expect($ebook->getSeries())->toBe('series');
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getPagesCount())->toBe(4);

    expect($ebook->toArray())->toBeArray();
    expect($ebook->toJson())->toBeString();
    expect($ebook->__toString())->toBeString();
});

it('can use BookContributor', function (string $content, string $role) {
    $item = new BookContributor($content, $role);

    expect($item->getContents())->toBe($content);
    expect($item->getRole())->toBe($role);
    expect($item->toArray())->toBe([
        'contents' => $content,
        'role' => $role,
    ]);
    expect($item->__toString())->toBe($content);
})->with([
    [
        'contents' => 'calibre',
        'role' => 'bkp',
    ],
    [
        'contents' => 'epbu2',
        'role' => 'bkp',
    ],
]);

it('can use BookAuthor', function (string $name, string $role) {
    $item = new BookAuthor($name, $role);

    expect($item->getName())->toBe($name);
    expect($item->getRole())->toBe($role);
    expect($item->toArray())->toBe([
        'name' => $name,
        'role' => $role,
    ]);
    expect($item->__toString())->toBe($name);
})->with([
    [
        'contents' => 'Jean M. Auel',
        'role' => 'aut',
    ],
    [
        'contents' => 'Terry Pratchett',
        'role' => 'aut',
    ],
]);

it('can use BookMeta', function (string $name, string $contents) {
    $item = new BookMeta($name, $contents);

    expect($item->getName())->toBe($name);
    expect($item->getContents())->toBe($contents);
    expect($item->toArray())->toBe([
        'name' => $name,
        'contents' => $contents,
    ]);
    expect($item->__toString())->toBe("{$name} {$contents}");
})->with([
    [
        'name' => 'calibre:title_sort',
        'contents' => "clan de l'ours des cavernes, Le",
    ],
    [
        'name' => 'calibre:series',
        'contents' => 'Les Enfants de la Terre',
    ],
    [
        'name' => 'calibre:series_index',
        'contents' => '1.0',
    ],
    [
        'name' => 'calibre:timestamp',
        'contents' => '2023-03-25T10:32:21+00:00',
    ],
    [
        'name' => 'calibre:rating',
        'contents' => '10.0',
    ],
]);

it('can parse with BookMeta', function (mixed $data) {
    $meta = BookMeta::parse($data);

    expect($meta)->toBeString();
})->with([
    'calibre:title_sort',
    1,
    true,
    [
        [
            '@attributes' => [
                'scheme' => 'calibre',
            ],
        ],
    ],
    [
        [
            '@attributes' => [
                'content' => "clan de l'ours des cavernes, Le",
            ],
        ],
    ],
    [
        [
            '@attributes' => [
                'contents' => "clan de l'ours des cavernes, Le",
            ],
        ],
    ],
    [
        [
            '@attributes' => [
                'role' => 'bkp',
            ],
        ],
    ],
    [
        [
            '@attributes' => [
                'title' => "clan de l'ours des cavernes, Le",
            ],
        ],
    ],
    (object) [
        'name' => 'calibre:title_sort',
        'content' => "clan de l'ours des cavernes, Le",
    ],
    [
        [
            'name' => 'calibre:title_sort',
            'content' => "clan de l'ours des cavernes, Le",
        ],
    ],
]);
