<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/House
 *
 * @property-read SchemaTypeList<Number|QuantitativeValue> $numberOfRooms The number of rooms (excluding bathrooms and closets) of the accommodation or lodging business.
 */
interface House extends Accommodation
{
}
