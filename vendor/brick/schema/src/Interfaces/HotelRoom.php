<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/HotelRoom
 *
 * @property-read SchemaTypeList<QuantitativeValue>       $occupancy The allowed total occupancy for the accommodation in persons (including infants etc). For individual accommodations, this is not necessarily the legal maximum but defines the permitted usage as per the contractual agreement (e.g. a double room used by a single person).
 * @property-read SchemaTypeList<BedDetails|BedType|Text> $bed       The type of bed or beds included in the accommodation. For the single case of just one bed of a certain type, you use bed directly with a text.
 */
interface HotelRoom extends Room
{
}
