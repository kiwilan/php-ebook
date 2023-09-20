<?php

namespace Kiwilan\Ebook\Formats\Mobi\Parser;

/**
 * @docs https://wiki.mobileread.com/wiki/Mobi
 */
class MobiReader
{
    const DRM_SERVER_ID_1 = 1;

    const DRM_COMMERCE_ID_2 = 2;

    const DRM_EBOOKBASE_BOOK_ID_3 = 3;

    const AUTHOR_100 = 100;

    const PUBLISHER_101 = 101;

    const IMPRINT_102 = 102;

    const DESCRIPTION_103 = 103;

    const ISBN_104 = 104;

    const SUBJECT_105 = 105;

    const PUBLISHINGDATE_106 = 106;

    const REVIEW_107 = 107;

    const CONTRIBUTOR_108 = 108;

    const RIGHTS_109 = 109;

    const SUBJECTCODE_110 = 110;

    const TYPE_111 = 111;

    const SOURCE_112 = 112;

    const ASIN_113 = 113;

    const VERSIONNUMBER_114 = 114;

    const SAMPLE_115 = 115;

    const STARTREADING_116 = 116;

    const ADULT_117 = 117;

    const RETAIL_PRICE_118 = 118;

    const RETAIL_PRICE_CURRENCY_119 = 119;

    const K_F8_BOUNDARY_OFFSET_121 = 121;

    const FIXED_LAYOUT_122 = 122;

    const BOOK_TYPE_123 = 123;

    const ORIENTATION_LOCK_124 = 124;

    const COUNT_OF_RESOURCES_125 = 125;

    const ORIGINAL_RESOLUTION_126 = 126;

    const ZERO_GUTTER_127 = 127;

    const ZERO_MARGIN_128 = 128;

    const METADATA_RESOURCE_URI_129 = 129;

    const UNKNOWN_131 = 131;

    const UNKNOWN_132 = 132;

    const DICTIONARY_SHORT_NAME_200 = 200;

    const COVEROFFSET_201 = 201;

    const THUMBOFFSET_202 = 202;

    const HASFAKECOVER_203 = 203;

    const CREATOR_SOFTWARE_204 = 204;

    const CREATOR_MAJOR_VERSION_205 = 205;

    const CREATOR_MINOR_VERSION_206 = 206;

    const CREATOR_BUILD_NUMBER_207 = 207;

    const WATERMARK_208 = 208;

    const TAMPER_PROOF_KEYS_209 = 209;

    const FONTSIGNATURE_300 = 300;

    const CLIPPINGLIMIT_401 = 401;

    const PUBLISHERLIMIT_402 = 402;

    const UNKNOWN_403 = 403;

    const TTSFLAG_404 = 404;

    const UNKNOWN_RENT_BORROW_FLAG_405 = 405;

    const RENT_BORROW_EXPIRATION_DATE_406 = 406;

    const UNKNOWN_407 = 407;

    const UNKNOWN_450 = 450;

    const UNKNOWN_451 = 451;

    const UNKNOWN_452 = 452;

    const UNKNOWN_453 = 453;

    const CDETYPE_501 = 501;

    const LASTUPDATETIME_502 = 502;

    const UPDATEDTITLE_503 = 503;

    const ASIN_504 = 504;

    const LANGUAGE_524 = 524;

    const WRITINGMODE_525 = 525;

    const CREATOR_BUILD_NUMBER_535 = 535;

    const UNKNOWN_536 = 536;

    const UNKNOWN_542 = 542;

    const IN_MEMORY_547 = 547;

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
