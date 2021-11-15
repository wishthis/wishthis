<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/TaxiReservation
 *
 * @property-read SchemaTypeList<Integer|QuantitativeValue> $partySize      Number of people the reservation should accommodate.
 * @property-read SchemaTypeList<Place>                     $pickupLocation Where a taxi will pick up a passenger or a rental car can be picked up.
 * @property-read SchemaTypeList<DateTime>                  $pickupTime     When a taxi will pickup a passenger or a rental car can be picked up.
 */
interface TaxiReservation extends Reservation
{
}
