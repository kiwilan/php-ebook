<?php

namespace Kiwilan\Ebook\Enums;

/**
 * For ComicRack's ComicInfo.xml
 *
 * @docs https://anansi-project.github.io/docs/comicinfo/schemas/v2.0
 */
enum MangaEnum: string
{
    case UNKNOWN = 'Unknown';

    case YES = 'Yes';

    case NO = 'No';

    case YES_AND_RIGHT_TO_LEFT = 'YesAndRightToLeft';
}
