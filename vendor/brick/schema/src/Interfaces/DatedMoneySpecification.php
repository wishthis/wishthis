<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/DatedMoneySpecification
 *
 * @property-read SchemaTypeList<DateTime|Date>         $endDate   The end date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Number|MonetaryAmount> $amount    The amount of money.
 * @property-read SchemaTypeList<Date|DateTime>         $startDate The start date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Text>                  $currency  The currency in which the monetary amount is expressed.
 */
interface DatedMoneySpecification extends StructuredValue
{
}
