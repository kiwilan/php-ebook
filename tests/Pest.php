<?php

// exiftool -Title="This is the Title" -Author="New Author" -Subject="Meta" pdf-example.pdf
define('PDF', __DIR__.'/media/pdf-example.pdf');
define('PDF_EMPTY', __DIR__.'/media/pdf-empty.pdf');
define('PDF_SIMPLE', __DIR__.'/media/pdf-simple.pdf');

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
define('EPUB_OPF_EPUB2_NO_TAGS', __DIR__.'/media/opf-epub2-no-tags.opf');
define('EPUB_OPF_EPUB3', __DIR__.'/media/opf-epub3.opf');
define('EPUB_OPF_EPUB3_ALT', __DIR__.'/media/opf-epub3-alt.opf');
define('EPUB_OPF_INSURGENT', __DIR__.'/media/opf-insurgent.opf');
define('EPUB_OPF_LAGUERREETERNELLE', __DIR__.'/media/opf-la-guerre-eternelle.opf');
define('EPUB_OPF_EPEEETMORT', __DIR__.'/media/opf-content-epee-et-mort.opf');
define('EPUB_OPF_NOT_FORMATTED', __DIR__.'/media/opf-not-formatted.opf');
define('EPUB_OPF_EMPTY_CREATOR', __DIR__.'/media/opf-epub2-empty-creator.opf');
define('EPUB_OPF_LA5EVAGUE', __DIR__.'/media/opf-content-la-5e-vague.opf');

define('EPUB', __DIR__.'/media/test-epub.epub');
define('EPUB_ONE_TAG', __DIR__.'/media/epub-one-tag.epub');
define('EPUB_NO_META', __DIR__.'/media/epub-no-meta.epub');
define('EPUB_MULTIPLE_CREATORS', __DIR__.'/media/epub-multiple-creators.epub');
define('EPUB_BAD_MULTIPLE_CREATORS', __DIR__.'/media/epub-bad-multiple-creators.epub');
define('EPUB_NO_CONTAINER', __DIR__.'/media/epub-no-container.epub');
define('EPUB_NO_OPF', __DIR__.'/media/epub-no-opf.epub');
define('EPUB_BAD_FILE', __DIR__.'/media/epub-bad-file.epub');
define('EPUB_DESCRIPTION', __DIR__.'/media/epub-description.epub');

define('STANDARD_EPUB', __DIR__.'/media/alice-lewis-carroll-1.epub');
define('STANDARD_AZW3', __DIR__.'/media/alice-lewis-carroll-2.azw3');
define('STANDARD_FB2', __DIR__.'/media/alice-lewis-carroll-3.fb2');
define('STANDARD_LRF', __DIR__.'/media/alice-lewis-carroll-4.lrf');
define('STANDARD_MOBI', __DIR__.'/media/alice-lewis-carroll-5.mobi');
define('STANDARD_PDB', __DIR__.'/media/alice-lewis-carroll-6.pdb');
define('STANDARD_SNB', __DIR__.'/media/alice-lewis-carroll-7.snb');

define('AUDIOBOOK', __DIR__.'/media/audiobook.mp3');
define('AUDIOBOOK_M4B', __DIR__.'/media/audiobook.m4b');
define('AUDIOBOOK_PART_1', __DIR__.'/media/audiobook-test-1.mp3');
define('AUDIOBOOK_PART_2', __DIR__.'/media/audiobook-test-2.mp3');
define('AUDIOBOOK_CHAPTERS', __DIR__.'/media/audiobook-test.m4b');

define('EBOOKS_ITEMS', [
    'EPUB' => EPUB,
    'CBZ' => CBZ,
    'PDF' => PDF,
]);

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

define('AUDIOBOOK_ITEMS', [
    'AUDIOBOOK' => AUDIOBOOK,
    'AUDIOBOOK_M4B' => AUDIOBOOK_M4B,
    'AUDIOBOOK_PART_1' => AUDIOBOOK_PART_1,
    'AUDIOBOOK_PART_2' => AUDIOBOOK_PART_2,
    'AUDIOBOOK_CHAPTERS' => AUDIOBOOK_CHAPTERS,
]);

define('STANDARD', [
    'EPUB' => STANDARD_EPUB,
    'AZW3' => STANDARD_AZW3,
    'FB2' => STANDARD_FB2,
    'LRF' => STANDARD_LRF,
    'MOBI' => STANDARD_MOBI,
    'PDB' => STANDARD_PDB,
    'SNB' => STANDARD_SNB,
]);

function outputPath(?string $path): string
{
    return __DIR__.'/output/'.$path;
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

function isBase64(?string $core): bool
{
    if (! $core) {
        return false;
    }

    if (base64_encode(base64_decode($core, true)) === $core) {
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
    foreach ($it as $ebook) {
        if ($ebook->isDir()) {
            rmdir($ebook->getPathname());
        } elseif (! in_array($ebook->getFilename(), $exclude)) {
            unlink($ebook->getPathname());
        }
    }
    // rmdir($dir);
}

/**
 * @return SplFileInfo[]
 */
function getAllFiles(string $path): array
{
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    $files = [];

    /** @var SplFileInfo $file */
    foreach ($rii as $file) {
        if ($file->isDir()) {
            continue;
        }

        $files[] = $file;
    }

    return $files;
}
