<?php

use Kiwilan\Ebook\Ebook;

it('can create epub', function () {
    $output = outputPath('epub-output');
    $path = outputPath('epub-creation.epub');
    if (file_exists($output)) {
        recurseRmdir($output);
    }
    if (file_exists($path)) {
        unlink($path);
    }

    $creator = Ebook::create($path);

    $original = Ebook::read(EPUB);
    $original->getArchive()->extractAll($output);

    $files = [];
    foreach (getAllFiles($output) as $file) {
        if ($file->isFile() && ! str_starts_with($file->getBasename(), '.')) {
            $files[] = $file;

            $relativePath = str_replace($output.'/', '', $file->getPathname());
            $creator->addFromString('mimetype', 'application/epub+zip')
                ->addFromString('custom/container.xml', '<?xml version="1.0"?><container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container"><rootfiles><rootfile full-path="content.opf" media-type="application/oebps-package+xml" /></rootfiles></container>')
                ->addFile($relativePath, $file->getPathname())
                ->save();

        }
    }

    $new = Ebook::read($path);

    expect($original->getTitle())->toBe($new->getTitle());
    expect($original->getAuthorMain()->getName())->toBe($new->getAuthorMain()->getName());
    expect($original->getDescription())->toBe($new->getDescription());
    expect($original->getDescriptionHtml())->toBe($new->getDescriptionHtml());
    expect($original->getCopyright())->toBe($new->getCopyright());
    expect($original->getPublisher())->toBe($new->getPublisher());
    expect($original->getIdentifiers()['uuid']->getValue())->toBe($new->getIdentifiers()['uuid']->getValue());
    expect($original->getPublishDate()->format('Y-m-d'))->toBe($new->getPublishDate()->format('Y-m-d'));
    expect($original->getLanguage())->toBe($new->getLanguage());
    expect($original->getTags())->toBe($new->getTags());
    expect($original->getSeries())->toBe($new->getSeries());
    expect($original->getVolume())->toBe($new->getVolume());
});

it('can create epub from directory', function () {
    $path = outputPath('epub-creation-dir.epub');
    $output = outputPath('epub-output');

    $original = Ebook::read(EPUB);
    $original->getArchive()->extractAll($output);

    Ebook::create($path)
        ->addDirectory('./', $output)
        ->save();

    $new = Ebook::read($path);

    expect($original->getTitle())->toBe($new->getTitle());
});

it('can create cbz', function () {
    $output = outputPath('cbz-output');
    $path = outputPath('cbz-creation.cbz');
    if (file_exists($output)) {
        recurseRmdir($output);
    }
    if (file_exists($path)) {
        unlink($path);
    }

    $creator = Ebook::create($path);

    $original = Ebook::read(CBZ);
    $original->getArchive()->extractAll($output);

    $files = [];
    foreach (getAllFiles($output) as $file) {
        if ($file->isFile() && ! str_starts_with($file->getBasename(), '.')) {
            $files[] = $file;

            $relativePath = str_replace($output.'/', '', $file->getPathname());
            $creator->addFile($relativePath, $file->getPathname())
                ->addFile($relativePath, $file->getPathname())
                ->save();
        }
    }

    $new = Ebook::read($path);

    expect($original->getTitle())->toBe($new->getTitle());
    expect($original->getAuthorMain()->getName())->toBe($new->getAuthorMain()->getName());
    expect($original->getPublisher())->toBe($new->getPublisher());
    expect($original->getSeries())->toBe($new->getSeries());
    expect($original->getVolume())->toBe($new->getVolume());
});
