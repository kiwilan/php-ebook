<?php

use Kiwilan\Ebook\Ebook;

it('can parse mobi', function () {
    $ebook = Ebook::read(MOBI);
    ray($ebook->metadata()->mobi());

    expect($ebook->title())->toBe("Alice's Adventures in Wonderland");
});
