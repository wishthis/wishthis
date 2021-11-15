<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/TVClip
 *
 * @property-read SchemaTypeList<TVSeries> $partOfTVSeries The TV series to which this episode or season belongs.
 */
interface TVClip extends Clip
{
}
