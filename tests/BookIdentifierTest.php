<?php

use Kiwilan\Ebook\Models\BookIdentifier;

it('can use book identifier', function (string $value, string $scheme) {
    $item = new BookIdentifier($value, $scheme);

    expect($item->getValue())->toBe($value);
    expect($item->getScheme())->toBe($scheme);
    expect($item->toArray())->toBe([
        'value' => $value,
        'scheme' => $scheme,
    ]);
    expect($item->__toString())->toBe("{$value} {$scheme}");
    if (str_contains($scheme, 'isbn')) {
        expect($item->getScheme())->toBe($scheme);
    }
})->with([
    [
        'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'uuid',
    ],
    [
        'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'uuid',
    ],
    [
        '63CTHAAACAAJ',
        'google',
    ],
    [
        '2744155349',
        'isbn10',
    ],
    [
        '9782266122122',
        'isbn13',
    ],
]);

it('can use advanced features', function (string $scheme, string $value) {
    $item = new BookIdentifier($value);

    expect($item->getValue())->toBe($value);
    expect($item->getScheme())->toBe($scheme);
})->with([
    [
        'isbn13',
        '9788075836663',
    ],
    [
        'qvn2sejbqufrqkfk',
        'ASvHBAAAQBAJ',
    ],
    [
        'uuid',
        'urn:uuid:10225bf5-b0ec-43e7-910a-e0e208623cd9',
    ],
    [
        'uuid',
        '10225bf5-b0ec-43e7-910a-e0e208623cd9',
    ],
    [
        'custom',
        'custom:customkey',
    ],
    [
        'doi',
        '10.1002/9781118257517',
    ],
    [
        'y3vzdg9ta2v5',
        'customkey',
    ],
]);

it('can use without auto detect', function (?string $scheme, ?string $value) {
    $item = new BookIdentifier($value, $scheme, autoDetect: false);

    expect($item->getValue())->toBe($value);
    expect($item->getScheme())->toBe($scheme);
})->with([
    [
        'isbn13',
        '9788075836663',
    ],
    [
        null,
        'ASvHBAAAQBAJ',
    ],
    [
        'custom',
        null,
    ],
]);
