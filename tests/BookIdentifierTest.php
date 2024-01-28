<?php

use Kiwilan\Ebook\Models\BookIdentifier;

it('can use BookIdentifier', function (string $value, string $scheme) {
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
        'content' => 'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'type' => 'uuid',
    ],
    [
        'content' => 'a2cf2f25-4de2-4f77-82cc-0198352b0851',
        'type' => 'uuid',
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

it('can use advanced features', function (string $scheme, string $value) {
    $item = new BookIdentifier($value);

    expect($item->getValue())->toBe($value);
    expect($item->getScheme())->toBe($scheme);
})->with([
    [
        'scheme' => 'isbn13',
        'value' => '9788075836663',
    ],
    [
        'scheme' => 'qvn2sejbqufrqkfk',
        'value' => 'ASvHBAAAQBAJ',
    ],
    [
        'scheme' => 'uuid',
        'value' => 'urn:uuid:10225bf5-b0ec-43e7-910a-e0e208623cd9',
    ],
    [
        'scheme' => 'uuid',
        'value' => '10225bf5-b0ec-43e7-910a-e0e208623cd9',
    ],
    [
        'scheme' => 'custom',
        'value' => 'custom:customkey',
    ],
    [
        'scheme' => 'doi',
        'value' => '10.1002/9781118257517',
    ],
    [
        'scheme' => 'y3vzdg9ta2v5',
        'value' => 'customkey',
    ],
]);

it('can use without autoDetect', function (?string $scheme, ?string $value) {
    $item = new BookIdentifier($value, $scheme, autoDetect: false);

    expect($item->getValue())->toBe($value);
    expect($item->getScheme())->toBe($scheme);
})->with([
    [
        'scheme' => 'isbn13',
        'value' => '9788075836663',
    ],
    [
        'scheme' => null,
        'value' => 'ASvHBAAAQBAJ',
    ],
    [
        'scheme' => 'custom',
        'value' => null,
    ],
]);
