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

    case CBR = 'cbr';

    case CBZ = 'cbz';

    case CB7 = 'cb7';

    case CBT = 'cbt';

    case CBC = 'cbc';

    case CHM = 'chm';

    case DJVU = 'djvu';

    case AUDIOBOOK = 'audiobook';
}
