<?php

use Kiwilan\Ebook\Ebook;
use Kiwilan\Ebook\Models\BookAuthor;
use Pest\Expectation;

it('can parse audiobook', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getpath())->toBe($path);
    expect($ebook->getTags())->toBeArray();

    $metadata = $ebook->getParser();
    expect($metadata->getAudiobook()->getAudio())->toBeArray();
    expect($ebook->getExtras())->toBeArray();
    expect($metadata->getAudiobook()->toArray())->toBeArray();
    expect($metadata->getAudiobook()->toJson())->toBeString();
    expect($metadata->getAudiobook()->__toString())->toBeString();
})->with(AUDIOBOOK_ITEMS);

it('can parse audiobook (basic)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getTitle())->toBe('P1PDD Le conclave de Troie');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    expect($ebook->getLanguage())->toBe('Language');
    expect($ebook->getPublisher())->toBeNull();
    expect($ebook->getExtra('comment'))->toBe('http://www.p1pdd.com');
    expect($ebook->getSeries())->toBeNull();
    expect($ebook->getVolume())->toBeNull();
    expect($ebook->getPagesCount())->toBe(11);
})->with([AUDIOBOOK, AUDIOBOOK_M4B, AUDIOBOOK_PART_1, AUDIOBOOK_PART_2]);

it('can parse audiobook (advanced)', function (string $path) {
    $ebook = Ebook::read($path);

    expect($ebook->getTitle())->toBe('Audiobook Test');
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthors())
        ->each(fn (Expectation $expectation) => expect($expectation->value)
            ->toBeInstanceOf(BookAuthor::class)
        );
    expect($ebook->getPublisher())->toBe('Ewilan Rivi&#232;re');
    expect($ebook->getDescription())->toBe('Description');
    expect($ebook->getExtra('comment'))->toBe('Do you want to extract an audiobook?');
    expect($ebook->getSeries())->toBeNull();
    expect($ebook->getVolume())->toBeNull();
    expect($ebook->getCopyright())->toBe('Copyright');
    expect($ebook->getPagesCount())->toBe(22);
})->with([AUDIOBOOK_CHAPTERS]);

it('can parse audiobook with series', function () {
    $ebook = Ebook::read(AUDIOBOOK_EWILAN);

    expect($ebook->getTitle())->toBe("La Quête d'Ewilan #01 : D'un monde à l'autre");
    expect($ebook->getAuthors())->toBeArray();
    expect($ebook->getAuthorMain()->getName())->toBe('Pierre Bottero');
    expect($ebook->getDescription())->toBeString();
    expect($ebook->getPublisher())->toBe('Rageot');
    expect($ebook->getIdentifiers()['isbn']->getValue())->toBe('9782253164692');
    expect($ebook->getSeries())->toBe("La Quête d'Ewilan");
    expect($ebook->getVolume())->toBe(1);
    expect($ebook->getPublishDate()->format('Y/m/d'))->toBe((new DateTime('2017-03-22 00:00:00'))->format('Y/m/d'));
    expect($ebook->getLanguage())->toBe('Français');
    expect($ebook->getTags())->toBeArray();
    expect($ebook->getTags())->toBe(['Fantasy', 'Épique']);

    expect($ebook->getExtra('subtitle'))->toBe('128 kbit/s');
    expect($ebook->getExtra('publish_year'))->toBe(2017);
    expect($ebook->getExtra('authors'))->toBeArray();
    expect($ebook->getExtra('narrators'))->toBeArray();
    expect($ebook->getExtra('lyrics'))->toBe("La Quête d'Ewilan #01");
    expect($ebook->getExtra('comment'))->toBe('French');
    expect($ebook->getExtra('synopsis'))->toBeString();
    expect($ebook->getExtra('chapters'))->toBeArray();
    expect($ebook->getExtra('chapters')[1])->toBe(['timestamp' => 11, 'title' => "D'un monde à l'autre"]);
    expect($ebook->getExtra('is_compilation'))->toBeFalse();
    expect($ebook->getExtra('encoding'))->toBe('Audiobook Builder');
    expect($ebook->getExtra('track_number'))->toBe('1/1');
    expect($ebook->getExtra('disc_number'))->toBeNull();
    expect($ebook->getExtra('stik'))->toBe('Audiobook');
    expect($ebook->getExtra('duration'))->toBe(33.0);
    expect($ebook->getExtra('audio_title'))->toBe('D\'un monde à l\'autre');
    expect($ebook->getExtra('audio_artist'))->toBeNull();
    expect($ebook->getExtra('audio_album'))->toBe("La Quête d'Ewilan #01 : D'un monde à l'autre");
    expect($ebook->getExtra('audio_album_artist'))->toBe('Pierre Bottero, Kelly Marot');
});

it('can parse audiobook with alt volume', function () {
    $ebook = Ebook::read(AUDIOBOOK_EWILAN_VOLUME);

    expect($ebook->getVolume())->toBe(1.1);
});

it('can parse audiobook with alt volume at zero', function () {
    $ebook = Ebook::read(AUDIOBOOK_EWILAN_VOLUME_ZERO);

    expect($ebook->getVolume())->toBe(0);
});

it('can parse audiobook without genres', function () {
    $ebook = Ebook::read(AUDIOBOOK_EWILAN_NO_GENRES);

    expect($ebook->getTags())->toBeEmpty();
});
