<?php

namespace Kiwilan\Ebook\Enums;

/**
 * For ComicRack's ComicInfo.xml
 *
 * @docs https://anansi-project.github.io/docs/comicinfo/schemas/v2.0
 */
enum AgeRatingEnum: string
{
    case UNKNOWN = 'Unknown';

    case ADULTS_ONLY_18_PLUS = 'Adults Only 18+';

    case EARLY_CHILDHOOD = 'Early Childhood';

    case EVERYONE = 'Everyone';

    case EVERYONE_10_PLUS = 'Everyone 10+';

    case G = 'G';

    case KIDS_TO_ADULTS = 'Kids to Adults';

    case M = 'M';

    case MA15_PLUS = 'MA15+';

    case MATURE_17_PLUS = 'Mature 17+';

    case PG = 'PG';

    case R18_PLUS = 'R18+';

    case RATING_PENDING = 'Rating Pending';

    case TEEN = 'Teen';

    case X18_PLUS = 'X18+';
}
