<?php

use Kiwilan\Ebook\Ebook;

it('can parse pdf', function () {
    $ebook = Ebook::read(PDF);
    $firstAuthor = $ebook->getAuthors()[0];

    expect($ebook->getpath())->toBe(PDF);

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBe('Example PDF');
    expect($ebook->getAuthors())->toBeArray();
    expect($firstAuthor->getName())->toBe('Ewilan Rivière');
    expect($ebook->getDescription())->toBeString();
    expect($ebook->getPublisher())->toBe('Kiwilan');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe('2023-03-21 07:44:27');
    expect($ebook->getTags())->toBeArray();
    expect($ebook->getPagesCount())->toBe(4);
});

it('can extract pdf cover', function () {
    $ebook = Ebook::read(PDF);

    $path = 'tests/output/cover-PDF.jpg';
    file_put_contents($path, $ebook->getCover()->getContent());

    expect($ebook->getCover()->getContent())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');
