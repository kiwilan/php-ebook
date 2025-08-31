<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Formats\Epub\EpubModule;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubChapter;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubContainer;
use Kiwilan\Ebook\Formats\Epub\Parser\EpubHtml;
use Kiwilan\Ebook\Formats\Epub\Parser\NcxItem;
use Kiwilan\Ebook\Formats\Epub\Parser\OpfItem;

it('can parse epub entity', function () {
    $ebook = Ebook::read(EPUB);
    $firstAuthor = $ebook->getAuthors()[0];
    $filename = pathinfo(EPUB, PATHINFO_FILENAME);
    $basename = pathinfo(EPUB, PATHINFO_BASENAME);

    expect($ebook->getpath())->toBe(EPUB);
    expect($ebook->getFilename())->toBe($filename);
    expect($ebook->getBasename())->toBe($basename);
    expect($ebook->hasParser())->toBeTrue();

    expect($ebook)->toBeInstanceOf(Ebook::class);
    expect($ebook->getTitle())->toBe('The Clan of the Cave Bear');
    expect($ebook->getAuthorMain()->getName())->toBe('Jean M. Auel');
    expect($ebook->getAuthors())->toBeArray();
    expect($firstAuthor->getName())->toBe('Jean M. Auel');
    expect($ebook->getDescription())->toBeString();
    expect($ebook->getDescriptionAdvanced()->getDescription(1500))->toBeString();
    expect($ebook->getCopyright(255))->toBe('Copyright © 1980 by Jean M. Auel');
    expect($ebook->getCopyright(10))->toBe('Copyrig…');
    expect($ebook->getCreatedAt())->toBeInstanceOf(DateTime::class);
    expect($ebook->getSize())->toBe(555895);
    expect($ebook->getSizeHumanReadable())->toBe('542.87 KB');
    expect($ebook->getPublisher())->toBe('Hodder & Stoughton');
    expect($ebook->getIdentifiers())->toBeArray();
    expect($ebook->getIdentifiers()['google']->getValue())->toBe('ASvHBAAAQBAJ');
    expect($ebook->getIdentifiers()['isbn13']->getValue())->toBe('9780345529329');
    expect($ebook->getPublishDate())->toBeInstanceOf(DateTime::class);
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe('1980-05-03 22:00:00');
    expect($ebook->getLanguage())->toBe('en');
    expect($ebook->getTags())->toBeArray();
    expect($ebook->getSeries())->toBe("Earth's Children");
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getPagesCount())->toBe(34);
    expect($ebook->getWordsCount())->toBeGreaterThan(8000);

    expect($ebook->getExtras())->toBeArray();
    expect($ebook->getExtras()['contributor'])->toBeString();
    expect($ebook->getExtras()['rating'])->toBeFloat();
    expect($ebook->getExtras()['rating'])->toBe(10.0);
    expect($ebook->getExtra('contributor'))->toBeString();
    expect($ebook->getExtra('contributora'))->toBeNull();

    $metadata = $ebook->getParser();
    expect($metadata->toArray())->toBeArray();
    expect($metadata->toJson())->toBeString();
    expect(Ebook::isValid(EPUB))->toBeTrue();
});

it('can get epub cover', function () {
    $ebook = Ebook::read(EPUB);
    $path = 'tests/output/cover-EPUB.jpg';
    file_put_contents($path, $ebook->getCover()->getContents());

    expect($ebook->getCover()->getPath())->toBeString();
    expect($ebook->getCover()->getContents())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
    expect(fileIsValidImg($path))->toBeTrue();
});

it('can get title meta', function () {
    $ebook = Ebook::read(EPUB);
    $meta = $ebook->getMetaTitle();

    expect($meta->getSlug())->toBe('earths-children-en-001-clan-of-the-cave-bear-jean-m-auel-1980-epub');
    expect($meta->getSeriesSlug())->toBe('earths-children-en');

    expect($meta->toArray())->toBeArray();
    expect($meta->__toString())->toBeString();
});

it('can extract alt metadata', function () {
    $ebook = Ebook::read(EPUB_NO_META);

    expect($ebook->getTitle())->toBe('epub-no-meta');
});

it('can read epub metadata', function () {
    $epub = Ebook::read(EPUB)->getParser()?->getEpub();

    $container = $epub->getContainer();
    $opf = $epub->getOpf();
    $ncx = $epub->getNcx();
    $chapters = $epub->getChapters();
    $files = $epub->getFiles();
    $html = $epub->getHtml();
    $wordsCount = $epub->getWordsCount();
    $pagesCount = $epub->getPagesCount();

    expect($container)->toBeInstanceOf(EpubContainer::class);
    expect($opf)->toBeInstanceOf(OpfItem::class);
    expect($ncx)->toBeInstanceOf(NcxItem::class);
    expect($chapters)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubChapter::class));
    expect($files)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeString());
    expect($html)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubHtml::class));
    expect($wordsCount)->toBeInt();
    expect($pagesCount)->toBeInt();
});

it('can read content', function () {
    $html = Ebook::read(EPUB)->getParser()?->getEpub()?->getHtml();

    foreach ($html as $value) {
        expect($value)->toBeInstanceOf(EpubHtml::class);
        expect($value->getFilename())->toBeString();
        expect($value->getHead())->toBeString();
        expect($value->getBody())->toBeString();

        expect($value->toArray())->toBeArray();
        expect($value->toJson())->toBeString();
        expect($value->__toString())->toBeString();
    }
});

it('can read ncx', function () {
    $ebook = Ebook::read(EPUB);
    $toc = $ebook->getParser()?->getEpub()?->getNcx();

    if ($toc) {
        expect($toc->getHead())->toBeArray();
        expect($toc->getDocTitle())->toBeString();
        expect($toc->getNavPoints())->toBeArray();
        expect($toc->getVersion())->toBeString();
        expect($toc->getLang())->toBeString();
    } else {
        expect($toc)->toBeNull();
    }
});

it('can build EPUB render', function () {
    $ebook = Ebook::read(EPUB);
    $chapters = $ebook->getParser()->getEpub()->getChapters();

    expect($chapters)->toBeArray();
});

it('can parse with good performances', function () {
    $ebook = Ebook::read(EPUB);

    expect($ebook->getExecTime())->toBeLessThan(0.3);
})->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse epub without tags', function () {
    $ebook = Ebook::read(EPUB_ONE_TAG);

    expect($ebook->getTags())->toBeArray();
    expect($ebook->getTags()[0])->toBeString();
});

it('can handle bad file', function () {
    $ebook = Ebook::read(EPUB_BAD_FILE);

    expect(Ebook::isValid(EPUB_BAD_FILE))->toBeFalse();
    expect($ebook->hasParser())->toBeFalse();
    expect($ebook->isBadFile())->toBeTrue();
    expect(fn () => $ebook->getArchive()->filter('opf'))->not()->toThrow(Exception::class);
});

it('can handle bad epub', function (string $epub) {
    $ebook = Ebook::read($epub);

    expect($ebook->hasParser())->toBeFalse();
})->with([
    EPUB_NO_CONTAINER,
    EPUB_NO_OPF,
]);

it('can parse description', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getDescription())->toBeString();
    expect($ebook->getDescription())->toBe("<div>
<p>1re vague : Extinction des feux.<br>2e vague : Déferlante.<br>3e vague : Pandémie.<br>4e vague : Silence.<br><br>À l'aube de la 5e vague, sur une autoroute désertée, Cassie tente de Leur échapper... Eux, ces êtres qui ressemblent trait pour trait aux humains et qui écument la campagne, exécutant quiconque a le malheur de croiser Leur chemin. Eux, qui ont balayé les dernières poches de résistance et dispersé les quelques rescapés.<br><br>Pour Cassie, rester en vie signifie rester seule. Elle se raccroche à cette règle jusqu'à ce qu'elle rencontre Evan Walker. Mystérieux et envoûtant, ce garçon pourrait bien être son ultime espoir de sauver son petit frère. Du moins si Evan est bien celui qu'il prétend...</p>
<p>Ils connaissent notre manière de penser.</p>
<p>Ils savent commentr nous exterminer.</p>
<p>Ils nous ont enlevé toute raison de vivre.</p>
<p>Ils viennent nous arracher ce pour quoi nous sommes prêts à mourir.</p></div>");
    expect($ebook->getDescriptionAdvanced()->toString())->toBe("1re vague : Extinction des feux. 2e vague : Déferlante. 3e vague : Pandémie. 4e vague : Silence. À l'aube de la 5e vague, sur une autoroute désertée, Cassie tente de Leur échapper...  Eux, ces êtres qui ressemblent trait pour trait aux humains et qui écument la campagne, exécutant quiconque a le malheur de croiser Leur chemin. Eux, qui ont balayé les dernières poches de résistance et dispersé les quelques rescapés. Pour Cassie, rester en vie signifie rester seule. Elle se raccroche à cette règle jusqu'à ce qu'elle rencontre Evan Walker. Mystérieux et envoûtant, ce garçon pourrait bien être son ultime espoir de sauver son petit frère. Du moins si Evan est bien celui qu'il prétend... Ils connaissent notre manière de penser. Ils savent commentr nous exterminer. Ils nous ont enlevé toute raison de vivre. Ils viennent nous arracher ce pour quoi nous sommes prêts à mourir.");
})->with([EPUB_DESCRIPTION])->skip(PHP_OS_FAMILY === 'Windows', 'Skip on Windows');

it('can parse epub with series and zero volume', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getVolume())->toBe(0);
})->with([EPUB_VOLZERO]);

it('can parse epub with series and float volume', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getVolume())->toBe(1.5);
    expect($ebook->getMetaTitle()?->getSlug())->toBe('enfants-de-la-terre-fr-001.5-clan-de-lours-des-cavernes-jean-m-auel-1980-epub');
})->with([EPUB_VOLFLOAT]);

it('can parse epub with bad summary', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getDescriptionAdvanced()->toString())->not()->toContain("\n");
})->with([EPUB_EPEE_ET_MORT]);

it('can read DRM epub', function () {
    $ebook = Ebook::read(EPUB_DRM);

    expect($ebook->getTitle())->toBe('Alana et l’enfant vampire');
    expect($ebook->getAuthorMain()->getName())->toBe('Cordélia');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors()[0]->getName())->toBe('Cordélia');
    expect($ebook->getPublisher())->toBe('Scrinéo');
    expect($ebook->getIdentifiers()['uuid']->getValue())->toBe('urn:uuid:10225bf5-b0ec-43e7-910a-e0e208623cd9');
    $date = new DateTime('2020-01-22 06:53:56');
    expect($ebook->getPublishDate()->format('Y-m-d H:i:s'))->toBe($date->format('Y-m-d H:i:s'));
    expect($ebook->getLanguage())->toBe('fr');
    expect($ebook->getCopyright())->toBe('© 2020 Scrineo');
    expect($ebook->getVolume())->toBeNull();

    $cover = $ebook->getCover();
    $path = 'tests/output/cover-EPUB-DRM.jpg';
    file_put_contents($path, $cover->getContents());

    expect($cover->getContents())->toBeString();
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
    expect(fileIsValidImg($path))->toBeFalse();

    $module = $ebook->getParser()->getEpub();

    $html = $module->getHtml();
    expect($html)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubHtml::class));

    $ebook->getCover()->saveTo('tests/output/cover-EPUB-DRM-save.jpg');
    expect(file_exists('tests/output/cover-EPUB-DRM-save.jpg'))->toBeTrue();
});

it('can get epub chapters', function () {
    $ebook = Ebook::read(EPUB);

    $epub = $ebook->getParser()->getEpub();
    expect($epub)->toBeInstanceOf(EpubModule::class);
    $chapters = $epub->getChapters();
    expect($chapters)->toBeArray()
        ->each(fn (Pest\Expectation $expectation) => expect($expectation->value)->toBeInstanceOf(EpubChapter::class));

    $firstChapter = $chapters[0];
    expect($firstChapter->getLabel())->toBe('Cover');
    expect($firstChapter->getSource())->toBe('titlepage.xhtml');
    expect($firstChapter->getContent())->toBeString();
});
