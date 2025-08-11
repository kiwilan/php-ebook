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
define('CBZ_CBAM_VOLUME', __DIR__.'/media/cba-cbam-volume.cbz');
define('CBZ_CBAM_NO_PAGES', __DIR__.'/media/cba-cbam-no-pages.cbz');
define('CBZ_CBAM_WEBP', __DIR__.'/media/cba-cbam-webp.cbz');

define('COMIC_INFO_BASIC', __DIR__.'/media/ComicInfoBasic.xml');
define('COMIC_INFO_SERIES_WITHOUT_VOLUME', __DIR__.'/media/ComicInfoSeriesWithoutVolume.xml');

define('EPUB_CONTAINER_EPUB2', __DIR__.'/media/container-epub2.xml');
define('EPUB_CONTAINER_EPUB2_BAD', __DIR__.'/media/container-epub2-bad.xml');
define('EPUB_CONTAINER_EPUB2_EMPTY', __DIR__.'/media/container-epub2-empty.xml');
define('EPUB_CONTAINER_EPUB3', __DIR__.'/media/container-epub3.xml');

define('EPUB_OPF_EPUB2', __DIR__.'/media/opf-epub2.opf');
define('EPUB_OPF_EPUB2_VOLUME_FLOAT', __DIR__.'/media/opf-epub-volume-float.opf');
define('EPUB_OPF_EPUB2_NO_TAGS', __DIR__.'/media/opf-epub2-no-tags.opf');
define('EPUB_OPF_EPUB3', __DIR__.'/media/opf-epub3.opf');
define('EPUB_OPF_EPUB3_ALT', __DIR__.'/media/opf-epub3-alt.opf');
define('EPUB_OPF_INSURGENT', __DIR__.'/media/opf-insurgent.opf');
define('EPUB_OPF_LAGUERREETERNELLE', __DIR__.'/media/opf-la-guerre-eternelle.opf');
define('EPUB_OPF_EPEEETMORT', __DIR__.'/media/opf-content-epee-et-mort.opf');
define('EPUB_OPF_NOT_FORMATTED', __DIR__.'/media/opf-not-formatted.opf');
define('EPUB_OPF_EMPTY_CREATOR', __DIR__.'/media/opf-epub2-empty-creator.opf');
define('EPUB_OPF_EMPTY_SUBJECT', __DIR__.'/media/opf-epub2-empty-subject.opf');
define('EPUB_OPF_LA5EVAGUE', __DIR__.'/media/opf-content-la-5e-vague.opf');
define('EPUB_OPF_MULTIPLE_AUTHORS', __DIR__.'/media/opf-epub2-multiple-authors.opf');
define('EPUB_OPF_MULTIPLE_AUTHORS_MERGE', __DIR__.'/media/opf-epub2-multiple-authors-merge.opf');

define('EPUB', __DIR__.'/media/test-epub.epub');
define('EPUB_ONE_TAG', __DIR__.'/media/epub-one-tag.epub');
define('EPUB_NO_META', __DIR__.'/media/epub-no-meta.epub');
define('EPUB_MULTIPLE_CREATORS', __DIR__.'/media/epub-multiple-creators.epub');
define('EPUB_BAD_MULTIPLE_CREATORS', __DIR__.'/media/epub-bad-multiple-creators.epub');
define('EPUB_NO_CONTAINER', __DIR__.'/media/epub-no-container.epub');
define('EPUB_NO_OPF', __DIR__.'/media/epub-no-opf.epub');
define('EPUB_BAD_FILE', __DIR__.'/media/epub-bad-file.epub');
define('EPUB_DESCRIPTION', __DIR__.'/media/epub-description.epub');
define('EPUB_VOLZERO', __DIR__.'/media/epub-volume-zero.epub');
define('EPUB_VOLFLOAT', __DIR__.'/media/epub-volume-float.epub');
define('EPUB_EPEE_ET_MORT', __DIR__.'/media/epub-epee-et-mort.epub');
define('EPUB_DRM', __DIR__.'/media/epub-drm.epub');

define('AUDIOBOOK', __DIR__.'/media/audiobook.mp3');
define('AUDIOBOOK_M4B', __DIR__.'/media/audiobook.m4b');
define('AUDIOBOOK_EWILAN', __DIR__.'/media/audiobook-ewilan.m4b');
define('AUDIOBOOK_PART_1', __DIR__.'/media/audiobook-test-1.mp3');
define('AUDIOBOOK_PART_2', __DIR__.'/media/audiobook-test-2.mp3');
define('AUDIOBOOK_CHAPTERS', __DIR__.'/media/audiobook-test.m4b');
define('AUDIOBOOK_EWILAN_VOLUME', __DIR__.'/media/audiobook-ewilan-volume.m4b');
define('AUDIOBOOK_EWILAN_VOLUME_ZERO', __DIR__.'/media/audiobook-ewilan-volume-0.m4b');
define('AUDIOBOOK_EWILAN_NO_GENRES', __DIR__.'/media/audiobook-ewilan-no-genres.m4b');

define('FORMAT_AZW3', __DIR__.'/media/alice-lewis-carroll.azw3');
define('FORMAT_DOCX', __DIR__.'/media/alice-lewis-carroll.docx');
define('FORMAT_EPUB', __DIR__.'/media/alice-lewis-carroll.epub');
define('FORMAT_FB2', __DIR__.'/media/alice-lewis-carroll.fb2');
define('FORMAT_HTMLZ', __DIR__.'/media/alice-lewis-carroll.htmlz');
define('FORMAT_KF8', __DIR__.'/media/alice-lewis-carroll.kf8');
define('FORMAT_LIT', __DIR__.'/media/alice-lewis-carroll.lit');
define('FORMAT_LRF', __DIR__.'/media/alice-lewis-carroll.lrf');
define('FORMAT_MOBI', __DIR__.'/media/alice-lewis-carroll.mobi');
define('FORMAT_PDB', __DIR__.'/media/alice-lewis-carroll.pdb');
define('FORMAT_PDF', __DIR__.'/media/alice-lewis-carroll.pdf');
define('FORMAT_PMLZ', __DIR__.'/media/alice-lewis-carroll.pmlz');
define('FORMAT_PRC', __DIR__.'/media/alice-lewis-carroll.prc');
define('FORMAT_RB', __DIR__.'/media/alice-lewis-carroll.rb');
define('FORMAT_RTF', __DIR__.'/media/alice-lewis-carroll.rtf');
define('FORMAT_SNB', __DIR__.'/media/alice-lewis-carroll.snb');
define('FORMAT_TCR', __DIR__.'/media/alice-lewis-carroll.tcr');
define('FORMAT_TXT', __DIR__.'/media/alice-lewis-carroll.txt');
define('FORMAT_TXTZ', __DIR__.'/media/alice-lewis-carroll.txtz');
define('FORMAT_ZIP', __DIR__.'/media/alice-lewis-carroll.zip');
define('FORMAT_DJVU', __DIR__.'/media/sample.djvu');

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
    if (! is_dir($dir)) {
        return;
    }

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

function fileIsValidImg(string $path): bool
{
    if (@is_array(getimagesize($path))) {
        return true;
    }

    return false;
}
