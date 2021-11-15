<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/LodgingReservation
 *
 * @property-read SchemaTypeList<Text>                      $lodgingUnitDescription A full description of the lodging unit.
 * @property-read SchemaTypeList<DateTime>                  $checkinTime            The earliest someone may check into a lodging establishment.
 * @property-read SchemaTypeList<Integer|QuantitativeValue> $numChildren            The number of children staying in the unit.
 * @property-read SchemaTypeList<DateTime>                  $checkoutTime           The latest someone may check out of a lodging establishment.
 * @property-read SchemaTypeList<QualitativeValue|Text>     $lodgingUnitType        Textual description of the unit type (including suite vs. room, size of bed, etc.).
 * @property-read SchemaTypeList<Integer|QuantitativeValue> $numAdults              The number of adults staying in the unit.
 */
interface LodgingReservation extends Reservation
{
}
