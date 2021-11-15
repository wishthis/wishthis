<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/BusTrip
 *
 * @property-read SchemaTypeList<Text>               $busName          The name of the bus (e.g. Bolt Express).
 * @property-read SchemaTypeList<BusStation|BusStop> $departureBusStop The stop or station from which the bus departs.
 * @property-read SchemaTypeList<BusStop|BusStation> $arrivalBusStop   The stop or station from which the bus arrives.
 * @property-read SchemaTypeList<Text>               $busNumber        The unique identifier for the bus.
 */
interface BusTrip extends Trip
{
}
