<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/TrainTrip
 *
 * @property-read SchemaTypeList<TrainStation> $departureStation  The station from which the train departs.
 * @property-read SchemaTypeList<Text>         $arrivalPlatform   The platform where the train arrives.
 * @property-read SchemaTypeList<Text>         $departurePlatform The platform from which the train departs.
 * @property-read SchemaTypeList<Text>         $trainName         The name of the train (e.g. The Orient Express).
 * @property-read SchemaTypeList<Text>         $trainNumber       The unique identifier for the train.
 * @property-read SchemaTypeList<TrainStation> $arrivalStation    The station where the train trip ends.
 */
interface TrainTrip extends Trip
{
}
