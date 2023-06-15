<?php

use Kiwilan\Ebook\Ebook;

it('can parse mobi', function () {
    $ebook = Ebook::read(MOBI);

    expect($ebook->title())->toBe("Alice's Adventures in Wonderland");
});
