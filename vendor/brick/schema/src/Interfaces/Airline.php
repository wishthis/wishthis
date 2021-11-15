<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Airline
 *
 * @property-read SchemaTypeList<Text>               $iataCode       IATA identifier for an airline or airport.
 * @property-read SchemaTypeList<BoardingPolicyType> $boardingPolicy The type of boarding policy used by the airline (e.g. zone-based or group-based).
 */
interface Airline extends Organization
{
}
