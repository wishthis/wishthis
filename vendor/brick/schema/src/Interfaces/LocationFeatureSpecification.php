<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/LocationFeatureSpecification
 *
 * @property-read SchemaTypeList<OpeningHoursSpecification> $hoursAvailable The hours during which this service or contact is available.
 * @property-read SchemaTypeList<DateTime>                  $validFrom      The date when the item becomes valid.
 * @property-read SchemaTypeList<DateTime>                  $validThrough   The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
 */
interface LocationFeatureSpecification extends PropertyValue
{
}
