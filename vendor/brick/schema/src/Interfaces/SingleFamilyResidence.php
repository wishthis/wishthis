<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/SingleFamilyResidence
 *
 * @property-read SchemaTypeList<QuantitativeValue>        $occupancy     The allowed total occupancy for the accommodation in persons (including infants etc). For individual accommodations, this is not necessarily the legal maximum but defines the permitted usage as per the contractual agreement (e.g. a double room used by a single person).
 * @property-read SchemaTypeList<Number|QuantitativeValue> $numberOfRooms The number of rooms (excluding bathrooms and closets) of the accommodation or lodging business.
 */
interface SingleFamilyResidence extends House
{
}
