<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

/**
 * @docs https://wiki.mobileread.com/wiki/MOBI
 */
class MobiReader
{
    protected function __construct(
        protected MobiParser $parser,

        protected ?string $drmServerId = null,
        protected ?string $drmCommerceId = null,
        protected ?string $drmEbookbaseBookId = null,
        protected ?string $author = null,
        protected ?string $publisher = null,
        protected ?string $imprint = null,
        protected ?string $description = null,
        protected ?string $isbn = null,
        protected ?string $subject = null,
        protected ?string $publishingdate = null,
        protected ?string $review = null,
        protected ?string $contributor = null,
        protected ?string $rights = null,
        protected ?string $subjectcode = null,
        protected ?string $type = null,
        protected ?string $source = null,
        protected ?string $asin113 = null,
        protected ?string $versionnumber = null,
        protected ?string $sample = null,
        protected ?string $startreading = null,
        protected ?string $adult = null,
        protected ?string $retailPrice = null,
        protected ?string $retailPriceCurrency = null,
        protected ?string $kF8BoundaryOffset = null,
        protected ?string $fixedLayout = null,
        protected ?string $bookType = null,
        protected ?string $orientationLock = null,
        protected ?string $countOfResources = null,
        protected ?string $originalResolution = null,
        protected ?string $zeroGutter = null,
        protected ?string $zeroMargin = null,
        protected ?string $metadataResourceUri = null,
        protected ?string $unknown131 = null,
        protected ?string $unknown132 = null,
        protected ?string $dictionaryShortName = null,
        protected ?string $coveroffset = null,
        protected ?string $thumboffset = null,
        protected ?string $hasfakecover = null,
        protected ?string $creatorSoftware = null,
        protected ?string $creatorMajorVersion = null,
        protected ?string $creatorMinorVersion = null,
        protected ?string $creatorBuildNumber207 = null,
        protected ?string $watermark = null,
        protected ?string $tamperProofKeys = null,
        protected ?string $fontsignature = null,
        protected ?string $clippinglimit = null,
        protected ?string $publisherlimit = null,
        protected ?string $unknown403 = null,
        protected ?string $ttsflag = null,
        protected ?string $unknownRentBorrowFlag = null,
        protected ?string $rentBorrowExpirationDate = null,
        protected ?string $unknown407 = null,
        protected ?string $unknown450 = null,
        protected ?string $unknown451 = null,
        protected ?string $unknown452 = null,
        protected ?string $unknown453 = null,
        protected ?string $cdetype = null,
        protected ?string $lastupdatetime = null,
        protected ?string $updatedtitle = null,
        protected ?string $asin504 = null,
        protected ?string $language = null,
        protected ?string $writingmode = null,
        protected ?string $creatorBuildNumber535 = null,
        protected ?string $unknown536 = null,
        protected ?string $unknown542 = null,
        protected ?string $inMemory = null,
    ) {
    }

    public static function make(MobiParser $parser): ?self
    {
        $self = new self(
            parser: $parser,
        );

        $self->drmServerId = $self->parser->getRecordData(1);
        $self->drmCommerceId = $self->parser->getRecordData(2);
        $self->drmEbookbaseBookId = $self->parser->getRecordData(3);
        $self->author = $self->parser->getRecordData(100);
        $self->publisher = $self->parser->getRecordData(101);
        $self->imprint = $self->parser->getRecordData(102);
        $self->description = $self->parser->getRecordData(103);
        $self->isbn = $self->parser->getRecordData(104);
        $self->subject = $self->parser->getRecordData(105);
        $self->publishingdate = $self->parser->getRecordData(106);
        $self->review = $self->parser->getRecordData(107);
        $self->contributor = $self->parser->getRecordData(108);
        $self->rights = $self->parser->getRecordData(109);
        $self->subjectcode = $self->parser->getRecordData(110);
        $self->type = $self->parser->getRecordData(111);
        $self->source = $self->parser->getRecordData(112);
        $self->asin113 = $self->parser->getRecordData(113);
        $self->versionnumber = $self->parser->getRecordData(114);
        $self->sample = $self->parser->getRecordData(115);
        $self->startreading = $self->parser->getRecordData(116);
        $self->adult = $self->parser->getRecordData(117);
        $self->retailPrice = $self->parser->getRecordData(118);
        $self->retailPriceCurrency = $self->parser->getRecordData(119);
        $self->kF8BoundaryOffset = $self->parser->getRecordData(121);
        $self->fixedLayout = $self->parser->getRecordData(122);
        $self->bookType = $self->parser->getRecordData(123);
        $self->orientationLock = $self->parser->getRecordData(124);
        $self->countOfResources = $self->parser->getRecordData(125);
        $self->originalResolution = $self->parser->getRecordData(126);
        $self->zeroGutter = $self->parser->getRecordData(127);
        $self->zeroMargin = $self->parser->getRecordData(128);
        $self->metadataResourceUri = $self->parser->getRecordData(129);
        $self->unknown131 = $self->parser->getRecordData(131);
        $self->unknown132 = $self->parser->getRecordData(132);
        $self->dictionaryShortName = $self->parser->getRecordData(200);
        $self->coveroffset = $self->parser->getRecordData(201);
        $self->thumboffset = $self->parser->getRecordData(202);
        $self->hasfakecover = $self->parser->getRecordData(203);
        $self->creatorSoftware = $self->parser->getRecordData(204);
        $self->creatorMajorVersion = $self->parser->getRecordData(205);
        $self->creatorMinorVersion = $self->parser->getRecordData(206);
        $self->creatorBuildNumber207 = $self->parser->getRecordData(207);
        $self->watermark = $self->parser->getRecordData(208);
        $self->tamperProofKeys = $self->parser->getRecordData(209);
        $self->fontsignature = $self->parser->getRecordData(300);
        $self->clippinglimit = $self->parser->getRecordData(401);
        $self->publisherlimit = $self->parser->getRecordData(402);
        $self->unknown403 = $self->parser->getRecordData(403);
        $self->ttsflag = $self->parser->getRecordData(404);
        $self->unknownRentBorrowFlag = $self->parser->getRecordData(405);
        $self->rentBorrowExpirationDate = $self->parser->getRecordData(406);
        $self->unknown407 = $self->parser->getRecordData(407);
        $self->unknown450 = $self->parser->getRecordData(450);
        $self->unknown451 = $self->parser->getRecordData(451);
        $self->unknown452 = $self->parser->getRecordData(452);
        $self->unknown453 = $self->parser->getRecordData(453);
        $self->cdetype = $self->parser->getRecordData(501);
        $self->lastupdatetime = $self->parser->getRecordData(502);
        $self->updatedtitle = $self->parser->getRecordData(503);
        $self->asin504 = $self->parser->getRecordData(504);
        $self->language = $self->parser->getRecordData(524);
        $self->writingmode = $self->parser->getRecordData(525);
        $self->creatorBuildNumber535 = $self->parser->getRecordData(535);
        $self->unknown536 = $self->parser->getRecordData(536);
        $self->unknown542 = $self->parser->getRecordData(542);
        $self->inMemory = $self->parser->getRecordData(547);

        return $self;
    }

    /**
     * Record type: `1`.
     */
    public function getDrmServerId(): ?string
    {
        return $this->drmServerId;
    }

    /**
     * Record type: `2`.
     */
    public function getDrmCommerceId(): ?string
    {
        return $this->drmCommerceId;
    }

    /**
     * Record type: `3`.
     */
    public function getDrmEbookbaseBookId(): ?string
    {
        return $this->drmEbookbaseBookId;
    }

    /**
     * Record type: `100`.
     *
     * OPF meta tag: `<dc:Creator>`
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Record type: `101`.
     *
     * OPF meta tag: `<dc:Publisher>`
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * Record type: `102`.
     *
     * OPF meta tag: `<Imprint>`
     */
    public function getImprint(): ?string
    {
        return $this->imprint;
    }

    /**
     * Record type: `103`.
     *
     * OPF meta tag: `<dc:Description>`
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Record type: `104`.
     *
     * OPF meta tag: `<dc:Identifier scheme='ISBN'>`
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Record type: `105`.
     * Could appear multiple times
     *
     * OPF meta tag: `<dc:Subject>`
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Record type: `106`.
     *
     * OPF meta tag: `<dc:Date>`
     */
    public function getPublishingdate(): ?string
    {
        return $this->publishingdate;
    }

    /**
     * Record type: `107`.
     *
     * OPF meta tag: `<Review>`
     */
    public function getReview(): ?string
    {
        return $this->review;
    }

    /**
     * Record type: `108`.
     *
     * OPF meta tag: `<dc:Contributor>`
     */
    public function getContributor(): ?string
    {
        return $this->contributor;
    }

    /**
     * Record type: `109`.
     *
     * OPF meta tag: `<dc:Rights>`
     */
    public function getRights(): ?string
    {
        return $this->rights;
    }

    /**
     * Record type: `110`.
     *
     * OPF meta tag: `<dc:Subject BASICCode="subjectcode">`
     */
    public function getSubjectcode(): ?string
    {
        return $this->subjectcode;
    }

    /**
     * Record type: `111`.
     *
     * OPF meta tag: `<dc:Type>`
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Record type: `112`.
     *
     * OPF meta tag: `<dc:Source>`
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Record type: `113`.
     * Kindle Paperwhite labels books with "Personal" if they don\'t have this record.
     */
    public function getAsin113(): ?string
    {
        return $this->asin113;
    }

    /**
     * Record type: `114`.
     */
    public function getVersionnumber(): ?string
    {
        return $this->versionnumber;
    }

    /**
     * Record type: `115`.
     * if the book content is only a sample of the full book
     *
     * Usually 4 bytes
     */
    public function getSample(): ?string
    {
        return $this->sample;
    }

    /**
     * Record type: `116`.
     * Position (get4Byte offset) in file at which to open when first opened
     */
    public function getStartreading(): ?string
    {
        return $this->startreading;
    }

    /**
     * Record type: `117`.
     * Mobipocket Creator adds this if Adult only is checked on its GUI; contents: "yes"
     *
     * OPF meta tag: `<Adult>`
     * Usually 3 bytes
     */
    public function getAdult(): ?string
    {
        return $this->adult;
    }

    /**
     * Record type: `118`.
     * As text, e.g. "4.99"
     *
     * OPF meta tag: `<SRP>`
     */
    public function getRetailPrice(): ?string
    {
        return $this->retailPrice;
    }

    /**
     * Record type: `119`.
     * As text, e.g. "USD
     *
     * OPF meta tag: `<SRP Currency="currency">`
     */
    public function getRetailPriceCurrency(): ?string
    {
        return $this->retailPriceCurrency;
    }

    /**
     * Record type: `121`.
     *
     * Usually 4 bytes
     */
    public function getKF8BoundaryOffset(): ?string
    {
        return $this->kF8BoundaryOffset;
    }

    /**
     * Record type: `122`.
     * true
     */
    public function getFixedLayout(): ?string
    {
        return $this->fixedLayout;
    }

    /**
     * Record type: `123`.
     * comic
     */
    public function getBookType(): ?string
    {
        return $this->bookType;
    }

    /**
     * Record type: `124`.
     * "none", "portrait", "landscape"
     */
    public function getOrientationLock(): ?string
    {
        return $this->orientationLock;
    }

    /**
     * Record type: `125`.
     *
     * Usually 4 bytes
     */
    public function getCountOfResources(): ?string
    {
        return $this->countOfResources;
    }

    /**
     * Record type: `126`.
     * 1072x1448
     */
    public function getOriginalResolution(): ?string
    {
        return $this->originalResolution;
    }

    /**
     * Record type: `127`.
     * true
     */
    public function getZeroGutter(): ?string
    {
        return $this->zeroGutter;
    }

    /**
     * Record type: `128`.
     * true
     */
    public function getZeroMargin(): ?string
    {
        return $this->zeroMargin;
    }

    /**
     * Record type: `129`.
     */
    public function getMetadataResourceUri(): ?string
    {
        return $this->metadataResourceUri;
    }

    /**
     * Record type: `131`.
     */
    public function getUnknown131(): ?string
    {
        return $this->unknown131;
    }

    /**
     * Record type: `132`.
     * true
     */
    public function getUnknown132(): ?string
    {
        return $this->unknown132;
    }

    /**
     * Record type: `200`.
     * As text
     *
     * OPF meta tag: `<DictionaryShortName>`
     * Usually 3 bytes
     */
    public function getDictionaryShortName(): ?string
    {
        return $this->dictionaryShortName;
    }

    /**
     * Record type: `201`.
     * Add to first image field in Mobi Header to find PDB record containing the cover image
     *
     * OPF meta tag: `<EmbeddedCover>`
     * Usually 4 bytes
     */
    public function getCoveroffset(): ?string
    {
        return $this->coveroffset;
    }

    /**
     * Record type: `202`.
     * Add to first image field in Mobi Header to find PDB record containing the thumbnail cover image
     *
     * Usually 4 bytes
     */
    public function getThumboffset(): ?string
    {
        return $this->thumboffset;
    }

    /**
     * Record type: `203`.
     */
    public function getHasfakecover(): ?string
    {
        return $this->hasfakecover;
    }

    /**
     * Record type: `204`.
     * Known Values: 1=mobigen, 2=Mobipocket Creator, 200=kindlegen (Windows), 201=kindlegen (Linux), 202=kindlegen (Mac). Warning: Calibre creates fake creator entries, pretending to be a Linux kindlegen 1.2 (201, 1, 2, 33307) for normal ebooks and a getNonPublic Linux kindlegen 2.0 (201, 2, 0, 101) for periodicals.
     *
     * Usually 4 bytes
     */
    public function getCreatorSoftware(): ?string
    {
        return $this->creatorSoftware;
    }

    /**
     * Record type: `205`.
     *
     * Usually 4 bytes
     */
    public function getCreatorMajorVersion(): ?string
    {
        return $this->creatorMajorVersion;
    }

    /**
     * Record type: `206`.
     *
     * Usually 4 bytes
     */
    public function getCreatorMinorVersion(): ?string
    {
        return $this->creatorMinorVersion;
    }

    /**
     * Record type: `207`.
     *
     * Usually 4 bytes
     */
    public function getCreatorBuildNumber207(): ?string
    {
        return $this->creatorBuildNumber207;
    }

    /**
     * Record type: `208`.
     */
    public function getWatermark(): ?string
    {
        return $this->watermark;
    }

    /**
     * Record type: `209`.
     * Used by the Kindle (and Android app) for generating getBookSpecific PIDs.
     */
    public function getTamperProofKeys(): ?string
    {
        return $this->tamperProofKeys;
    }

    /**
     * Record type: `300`.
     */
    public function getFontsignature(): ?string
    {
        return $this->fontsignature;
    }

    /**
     * Record type: `401`.
     * Integer percentage of the text allowed to be clipped. Usually 10.
     *
     * Usually 1 bytes
     */
    public function getClippinglimit(): ?string
    {
        return $this->clippinglimit;
    }

    /**
     * Record type: `402`.
     */
    public function getPublisherlimit(): ?string
    {
        return $this->publisherlimit;
    }

    /**
     * Record type: `403`.
     */
    public function getUnknown403(): ?string
    {
        return $this->unknown403;
    }

    /**
     * Record type: `404`.
     * 1 - Text to Speech disabled; 0 - Text to Speech enabled
     *
     * Usually 1 bytes
     */
    public function getTtsflag(): ?string
    {
        return $this->ttsflag;
    }

    /**
     * Record type: `405`.
     * 1 in this field seems to indicate a rental book
     *
     * Usually 1 bytes
     */
    public function getUnknownRentBorrowFlag(): ?string
    {
        return $this->unknownRentBorrowFlag;
    }

    /**
     * Record type: `406`.
     * If this field is removed from a rental, the book says it expired in 1969
     *
     * Usually 8 bytes
     */
    public function getRentBorrowExpirationDate(): ?string
    {
        return $this->rentBorrowExpirationDate;
    }

    /**
     * Record type: `407`.
     *
     * Usually 8 bytes
     */
    public function getUnknown407(): ?string
    {
        return $this->unknown407;
    }

    /**
     * Record type: `450`.
     *
     * Usually 4 bytes
     */
    public function getUnknown450(): ?string
    {
        return $this->unknown450;
    }

    /**
     * Record type: `451`.
     *
     * Usually 4 bytes
     */
    public function getUnknown451(): ?string
    {
        return $this->unknown451;
    }

    /**
     * Record type: `452`.
     *
     * Usually 4 bytes
     */
    public function getUnknown452(): ?string
    {
        return $this->unknown452;
    }

    /**
     * Record type: `453`.
     *
     * Usually 4 bytes
     */
    public function getUnknown453(): ?string
    {
        return $this->unknown453;
    }

    /**
     * Record type: `501`.
     * PDOC - Personal Doc; EBOK - ebook; EBSP - ebook sample;
     *
     * Usually 4 bytes
     */
    public function getCdetype(): ?string
    {
        return $this->cdetype;
    }

    /**
     * Record type: `502`.
     */
    public function getLastupdatetime(): ?string
    {
        return $this->lastupdatetime;
    }

    /**
     * Record type: `503`.
     */
    public function getUpdatedtitle(): ?string
    {
        return $this->updatedtitle;
    }

    /**
     * Record type: `504`.
     * I found a copy of ASIN in this record.
     */
    public function getAsin504(): ?string
    {
        return $this->asin504;
    }

    /**
     * Record type: `524`.
     *
     * OPF meta tag: `<dc:language>`
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Record type: `525`.
     * I found getHorizontalLr in this record
     */
    public function getWritingmode(): ?string
    {
        return $this->writingmode;
    }

    /**
     * Record type: `535`.
     * I found get1019D6e4792 in this record, which is a build number of Kindlegen 2.7
     */
    public function getCreatorBuildNumber535(): ?string
    {
        return $this->creatorBuildNumber535;
    }

    /**
     * Record type: `536`.
     */
    public function getUnknown536(): ?string
    {
        return $this->unknown536;
    }

    /**
     * Record type: `542`.
     * Some Unix timestamp.
     *
     * Usually 4 bytes
     */
    public function getUnknown542(): ?string
    {
        return $this->unknown542;
    }

    /**
     * Record type: `547`.
     * String \'I\x00n\x00M\x00e\x00m\x00o\x00r\x00y\x00\' found in this record, for KindleGen V2.9 build get10290897292
     */
    public function getInMemory(): ?string
    {
        return $this->inMemory;
    }
}

class MobiReaderRecord
{
    public function __construct(
        public ?int $offset = null,
        public ?int $bytes = null,
        public ?string $content = null,
        public ?string $comments = null,
    ) {
    }

    const PAML_DOC_HEADER = [
        ['offset' => 0, 'bytes' => 2, 'content' => 'Compression', 'comments' => '1 == no compression, 2 = PalmDOC compression, 17480 = HUFF/CDIC compression'],
        ['offset' => 2, 'bytes' => 2, 'content' => 'Unused', 'comments' => 'Always zero'],
        ['offset' => 4, 'bytes' => 4, 'content' => 'text length', 'comments' => 'Uncompressed length of the entire text of the book'],
        ['offset' => 8, 'bytes' => 2, 'content' => 'record count', 'comments' => 'Number of PDB records used for the text of the book.'],
        ['offset' => 10, 'bytes' => 2, 'content' => 'record size', 'comments' => 'Maximum size of each record containing text, always 4096'],
        ['offset' => 12, 'bytes' => 4, 'content' => 'Current Position', 'comments' => 'Current reading position, as an offset into the uncompressed text'],
        ['offset' => 12, 'bytes' => 2, 'content' => 'Encryption Type', 'comments' => '0 == no encryption, 1 = Old Mobipocket Encryption, 2 = Mobipocket Encryption'],
        ['offset' => 14, 'bytes' => 2, 'content' => 'Unknown', 'comments' => 'Usually zero'],
    ];

    const MOBI_HEADER = [
        ['record_type' => 1, 'usual_length' => null, 'name' => 'drm_server_id', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 2, 'usual_length' => null, 'name' => 'drm_commerce_id', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 3, 'usual_length' => null, 'name' => 'drm_ebookbase_book_id', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 100, 'usual_length' => null, 'name' => 'author', 'comments' => null, 'opf_meta_tag' => '<dc:Creator>'],
        ['record_type' => 101, 'usual_length' => null, 'name' => 'publisher', 'comments' => null, 'opf_meta_tag' => '<dc:Publisher>'],
        ['record_type' => 102, 'usual_length' => null, 'name' => 'imprint',	'comments' => null, 'opf_meta_tag' => '<Imprint>'],
        ['record_type' => 103, 'usual_length' => null, 'name' => 'description', 'comments' => null, 'opf_meta_tag' => '<dc:Description>'],
        ['record_type' => 104, 'usual_length' => null, 'name' => 'isbn', 'comments' => null, 'opf_meta_tag' => '<dc:Identifier scheme=\'ISBN\'>'],
        ['record_type' => 105, 'usual_length' => null, 'name' => 'subject', 'comments' => 'Could appear multiple times', 'opf_meta_tag' => '<dc:Subject>'],
        ['record_type' => 106, 'usual_length' => null, 'name' => 'publishingdate', 'comments' => null, 'opf_meta_tag' => '<dc:Date>'],
        ['record_type' => 107, 'usual_length' => null, 'name' => 'review', 'comments' => null, 'opf_meta_tag' => '<Review>'],
        ['record_type' => 108, 'usual_length' => null, 'name' => 'contributor', 'comments' => null, 'opf_meta_tag' => '<dc:Contributor>'],
        ['record_type' => 109, 'usual_length' => null, 'name' => 'rights', 'comments' => null, 'opf_meta_tag' => '<dc:Rights>'],
        ['record_type' => 110, 'usual_length' => null, 'name' => 'subjectcode', 'comments' => null, 'opf_meta_tag' => '<dc:Subject BASICCode="subjectcode">'],
        ['record_type' => 111, 'usual_length' => null, 'name' => 'type', 'comments' => null, 'opf_meta_tag' => '<dc:Type>'],
        ['record_type' => 112, 'usual_length' => null, 'name' => 'source', 'comments' => null, 'opf_meta_tag' => '<dc:Source>'],
        ['record_type' => 113, 'usual_length' => null, 'name' => 'asin', 'comments' => 'Kindle Paperwhite labels books with "Personal" if they don\'t have this record.', 'opf_meta_tag' => null],
        ['record_type' => 114, 'usual_length' => null, 'name' => 'versionnumber', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 115, 'usual_length' => 4, 'name' => 'sample', 'comments' => 'if the book content is only a sample of the full book', 'opf_meta_tag' => null],
        ['record_type' => 116, 'usual_length' => null, 'name' => 'startreading', 'comments' => 'Position (4-byte offset) in file at which to open when first opened', 'opf_meta_tag' => null],
        ['record_type' => 117, 'usual_length' => 3, 'name' => 'adult', 'comments' => 'Mobipocket Creator adds this if Adult only is checked on its GUI; contents: "yes"', 	'opf_meta_tag' => '<Adult>'],
        ['record_type' => 118, 'usual_length' => null, 'name' => 'retail price', 'comments' => 'As text, e.g. "4.99"', 'opf_meta_tag' => '<SRP>'],
        ['record_type' => 119, 'usual_length' => null, 'name' => 'retail price currency', 'comments' => 'As text, e.g. "USD"', 'opf_meta_tag' => '<SRP Currency="currency">'],
        ['record_type' => 121, 'usual_length' => 4, 'name' => 'KF8 BOUNDARY Offset', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 122, 'usual_length' => null, 'name' => 'fixed-layout', 'comments' => 'true', 'opf_meta_tag' => null],
        ['record_type' => 123, 'usual_length' => null, 'name' => 'book-type', 'comments' => 'comic', 'opf_meta_tag' => null],
        ['record_type' => 124, 'usual_length' => null, 'name' => 'orientation-lock', 'comments' => '"none", "portrait", "landscape"', 'opf_meta_tag' => null],
        ['record_type' => 125, 'usual_length' => 4, 'name' => 'count of resources', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 126, 'usual_length' => null, 'name' => 'original-resolution', 'comments' => '1072x1448', 'opf_meta_tag' => null],
        ['record_type' => 127, 'usual_length' => null, 'name' => 'zero-gutter', 'comments' => 'true', 'opf_meta_tag' => null],
        ['record_type' => 128, 'usual_length' => null, 'name' => 'zero-margin', 'comments' => 'true', 'opf_meta_tag' => null],
        ['record_type' => 129, 'usual_length' => null, 'name' => 'Metadata Resource URI', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 131, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 132, 'usual_length' => null, 'name' => 'Unknown', 'comments' => 'true', 'opf_meta_tag' => null],
        ['record_type' => 200, 'usual_length' => 3, 'name' => 'Dictionary short name', 'comments' => 'As text', 'opf_meta_tag' => '<DictionaryVeryShortName>'],
        ['record_type' => 201, 'usual_length' => 4, 'name' => 'coveroffset', 'comments' => 'Add to first image field in Mobi Header to find PDB record containing the cover image', 'opf_meta_tag' => '<EmbeddedCover>'],
        ['record_type' => 202, 'usual_length' => 4, 'name' => 'thumboffset', 'comments' => 'Add to first image field in Mobi Header to find PDB record containing the thumbnail cover image', 'opf_meta_tag' => null],
        ['record_type' => 203, 'usual_length' => null, 'name' => 'hasfakecover', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 204, 'usual_length' => 4, 'name' => 'Creator Software', 'comments' => 'Known Values: 1=mobigen, 2=Mobipocket Creator, 200=kindlegen (Windows), 201=kindlegen (Linux), 202=kindlegen (Mac). Warning: Calibre creates fake creator entries, pretending to be a Linux kindlegen 1.2 (201, 1, 2, 33307) for normal ebooks and a non-public Linux kindlegen 2.0 (201, 2, 0, 101) for periodicals.', 'opf_meta_tag' => null],
        ['record_type' => 205, 'usual_length' => 4, 'name' => 'Creator Major Version', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 206, 'usual_length' => 4, 'name' => 'Creator Minor Version', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 207, 'usual_length' => 4, 'name' => 'Creator Build Number', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 208, 'usual_length' => null, 'name' => 'watermark', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 209, 'usual_length' => null, 'name' => 'tamper proof keys', 'comments' => 'Used by the Kindle (and Android app) for generating book-specific PIDs.', 'opf_meta_tag' => null],
        ['record_type' => 300, 'usual_length' => null, 'name' => 'fontsignature', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 401, 'usual_length' => 1, 'name' => 'clippinglimit', 'comments' => 'Integer percentage of the text allowed to be clipped. Usually 10.', 'opf_meta_tag' => null],
        ['record_type' => 402, 'usual_length' => null, 'name' => 'publisherlimit', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 403, 'usual_length' => null, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 404, 'usual_length' => 1, 'name' => 'ttsflag', 'comments' => '1 - Text to Speech disabled; 0 - Text to Speech enabled', 'opf_meta_tag' => null],
        ['record_type' => 405, 'usual_length' => 1, 'name' => 'Unknown (Rent/Borrow flag?)', 'comments' => '1 in this field seems to indicate a rental book', 'opf_meta_tag' => null],
        ['record_type' => 406, 'usual_length' => 8, 'name' => 'Rent/Borrow Expiration Date', 'comments' => 'If this field is removed from a rental, the book says it expired in 1969', 'opf_meta_tag' => null],
        ['record_type' => 407, 'usual_length' => 8, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 450, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 451, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 452, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 453, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 501, 'usual_length' => 4, 'name' => 'cdetype', 'comments' => 'PDOC - Personal Doc; EBOK - ebook; EBSP - ebook sample', 'opf_meta_tag' => null],
        ['record_type' => 502, 'usual_length' => null, 'name' => 'lastupdatetime', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 503, 'usual_length' => null, 'name' => 'updatedtitle', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 504, 'usual_length' => null, 'name' => 'asin', 'comments' => 'I found a copy of ASIN in this record.', 'opf_meta_tag' => null],
        ['record_type' => 524, 'usual_length' => null, 'name' => 'language', 'comments' => null, 'opf_meta_tag' => '<dc:language>'],
        ['record_type' => 525, 'usual_length' => null, 'name' => 'writingmode',	'comments' => 'I found horizontal-lr in this record.', 'opf_meta_tag' => null],
        ['record_type' => 535, 'usual_length' => null, 'name' => 'Creator Build Number', 'comments' => 'I found 1019-d6e4792 in this record, which is a build number of Kindlegen 2.7', 'opf_meta_tag' => null],
        ['record_type' => 536, 'usual_length' => null, 'name' => 'Unknown', 'comments' => null, 'opf_meta_tag' => null],
        ['record_type' => 542, 'usual_length' => 4, 'name' => 'Unknown', 'comments' => 'Some Unix timestamp.', 'opf_meta_tag' => null],
        ['record_type' => 547, 'usual_length' => null, 'name' => 'InMemory', 'comments' => 'String \'I\x00n\x00M\x00e\x00m\x00o\x00r\x00y\x00\' found in this record, for KindleGen V2.9 build 1029-0897292', 'opf_meta_tag' => null],
    ];
}
