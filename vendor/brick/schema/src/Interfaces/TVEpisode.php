<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/TVEpisode
 *
 * @property-read SchemaTypeList<TVSeries>      $partOfTVSeries   The TV series to which this episode or season belongs.
 * @property-read SchemaTypeList<Language|Text> $subtitleLanguage Languages in which subtitles/captions are available, in IETF BCP 47 standard format.
 * @property-read SchemaTypeList<Country>       $countryOfOrigin  The country of the principal offices of the production company or individual responsible for the movie or program.
 */
interface TVEpisode extends Episode
{
}
