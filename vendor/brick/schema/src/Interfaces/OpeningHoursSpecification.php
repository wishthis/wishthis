<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Time;

/**
 * http://schema.org/OpeningHoursSpecification
 *
 * @property-read SchemaTypeList<DateTime>  $validFrom    The date when the item becomes valid.
 * @property-read SchemaTypeList<DateTime>  $validThrough The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
 * @property-read SchemaTypeList<Time>      $opens        The opening hour of the place or service on the given day(s) of the week.
 * @property-read SchemaTypeList<Time>      $closes       The closing hour of the place or service on the given day(s) of the week.
 * @property-read SchemaTypeList<DayOfWeek> $dayOfWeek    The day of the week for which these opening hours are valid.
 */
interface OpeningHoursSpecification extends StructuredValue
{
}
