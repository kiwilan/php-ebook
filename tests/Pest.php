<?php

define('PDF', __DIR__.'/media/example.pdf');

define('CBZ_NO_METADATA', __DIR__.'/media/cba-no-metadata.cbz');
define('CBZ', __DIR__.'/media/cba.cbz');
define('CBR', __DIR__.'/media/cba.cbr');
define('CBT', __DIR__.'/media/cba.cbt');
define('CB7', __DIR__.'/media/cba.cb7');

define('CBZ_CBAM', __DIR__.'/media/cba-cbam.cbz');
define('CBZ_CBML', __DIR__.'/media/cba-cbml.cbz');
define('CBZ_CRM', __DIR__.'/media/cba-crm.cbz');

define('COMIC_INFO_BASIC', __DIR__.'/media/ComicInfoBasic.xml');

define('EPUB_CONTAINER_EPUB2', __DIR__.'/media/container-epub2.xml');
define('EPUB_CONTAINER_EPUB2_BAD', __DIR__.'/media/container-epub2-bad.xml');
define('EPUB_CONTAINER_EPUB2_EMPTY', __DIR__.'/media/container-epub2-empty.xml');
define('EPUB_CONTAINER_EPUB3', __DIR__.'/media/container-epub3.xml');

define('EPUB_OPF_EPUB2', __DIR__.'/media/opf-epub2.opf');
define('EPUB_OPF_EPUB3', __DIR__.'/media/opf-epub3.opf');
define('EPUB_OPF_EPUB3_ALT', __DIR__.'/media/opf-epub3-alt.opf');

define('EPUB', __DIR__.'/media/test-epub.epub');
define('EPUB_NO_META', __DIR__.'/media/epub-no-meta.epub');
define('EPUB_MULTIPLE_CREATORS', __DIR__.'/media/epub-multiple-creators.epub');
define('EPUB_BAD_MULTIPLE_CREATORS', __DIR__.'/media/epub-bad-multiple-creators.epub');
define('BOOKS_ITEMS', [
    'EPUB' => EPUB,
    'EPUB_MULTIPLE_CREATORS' => EPUB_MULTIPLE_CREATORS,
    'EPUB_BAD_MULTIPLE_CREATORS' => EPUB_BAD_MULTIPLE_CREATORS,
]);

define('CBA_ITEMS', [
    'CBZ' => CBZ,
    'CBR' => CBR,
    // 'CBT' => CBT,
    'CB7' => CB7,
]);

function outputPath(): string
{
    return __DIR__.'/output/';
}

function outputPathFake(): string
{
    return __DIR__.'/outpu/';
}

function isImage(?string $extension): bool
{
    return in_array($extension, [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'webp',
        'svg',
        'ico',
        'avif',
    ], true);
}

function isBase64(?string $data): bool
{
    if (! $data) {
        return false;
    }

    if (base64_encode(base64_decode($data, true)) === $data) {
        return true;
    }

    return false;
}

function isHiddenFile(string $path): bool
{
    return substr($path, 0, 1) === '.';
}

function base64ToImage(?string $base64, string $path): bool
{
    if (! $base64) {
        return false;
    }

    $content = base64_decode($base64, true);
    $res = file_put_contents($path, $content);

    return $res;
}

function stringToImage(?string $content, string $path): bool
{
    if (! $content) {
        return false;
    }

    $res = file_put_contents($path, $content);

    return $res;
}

function listFiles(string $dir): array
{
    $files = array_diff(scandir($dir), ['.', '..', '.gitignore']);

    $items = [];
    foreach ($files as $file) {
        if (! is_dir("$dir/$file") && ! is_link("$dir/$file")) {
            $items[] = $file;
        } else {
            $items = array_merge($items, listFiles("$dir/$file"));
        }
    }

    return $items;
}

function recurseRmdir(string $dir)
{
    $exclude = ['.gitignore'];
    $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } elseif (! in_array($file->getFilename(), $exclude)) {
            unlink($file->getPathname());
        }
    }
    // rmdir($dir);
}
