<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Airport
 *
 * @property-read SchemaTypeList<Text> $iataCode IATA identifier for an airline or airport.
 * @property-read SchemaTypeList<Text> $icaoCode ICAO identifier for an airport.
 */
interface Airport extends CivicStructure
{
}
