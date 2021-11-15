<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/RentalCarReservation
 *
 * @property-read SchemaTypeList<DateTime> $dropoffTime     When a rental car can be dropped off.
 * @property-read SchemaTypeList<Place>    $dropoffLocation Where a rental car can be dropped off.
 * @property-read SchemaTypeList<Place>    $pickupLocation  Where a taxi will pick up a passenger or a rental car can be picked up.
 * @property-read SchemaTypeList<DateTime> $pickupTime      When a taxi will pickup a passenger or a rental car can be picked up.
 */
interface RentalCarReservation extends Reservation
{
}
