<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/TVSeason
 *
 * @property-read SchemaTypeList<TVSeries> $partOfTVSeries  The TV series to which this episode or season belongs.
 * @property-read SchemaTypeList<Country>  $countryOfOrigin The country of the principal offices of the production company or individual responsible for the movie or program.
 */
interface TVSeason extends CreativeWork, CreativeWorkSeason
{
}
