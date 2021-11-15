<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/FlightReservation
 *
 * @property-read SchemaTypeList<Text>                  $passengerSequenceNumber The passenger's sequence number as assigned by the airline.
 * @property-read SchemaTypeList<Text>                  $securityScreening       The type of security screening the passenger is subject to.
 * @property-read SchemaTypeList<QualitativeValue|Text> $passengerPriorityStatus The priority status assigned to a passenger for security or boarding (e.g. FastTrack or Priority).
 * @property-read SchemaTypeList<Text>                  $boardingGroup           The airline-specific indicator of boarding order / preference.
 */
interface FlightReservation extends Reservation
{
}
