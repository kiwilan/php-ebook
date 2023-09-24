<?php

namespace Kiwilan\Ebook\Enums;

enum EbookFormatEnum: string
{
    case EPUB = 'epub';

    case PDF = 'pdf';

    case CBA = 'cba';

    case MOBI = 'mobi';

    case AZW = 'azw';

    case AZW3 = 'azw3';

    case CHM = 'chm';

    case DJVU = 'djvu';

    case LRF = 'lrf';

    case FB2 = 'fb2';

    case RTF = 'rtf';

    case AUDIOBOOK = 'audiobook';
}
