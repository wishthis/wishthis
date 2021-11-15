<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Seat
 *
 * @property-read SchemaTypeList<Text>                  $seatRow     The row location of the reserved seat (e.g., B).
 * @property-read SchemaTypeList<QualitativeValue|Text> $seatingType The type/class of the seat.
 * @property-read SchemaTypeList<Text>                  $seatSection The section location of the reserved seat (e.g. Orchestra).
 * @property-read SchemaTypeList<Text>                  $seatNumber  The location of the reserved seat (e.g., 27).
 */
interface Seat extends Intangible
{
}
