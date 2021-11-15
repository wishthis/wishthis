<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/ReservationPackage
 *
 * @property-read SchemaTypeList<Reservation> $subReservation The individual reservations included in the package. Typically a repeated property.
 */
interface ReservationPackage extends Reservation
{
}
