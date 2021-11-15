<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/MonetaryAmount
 *
 * @property-read SchemaTypeList<Number|Text|StructuredValue|Boolean> $value        The value of the quantitative value or property value node.
 * @property-read SchemaTypeList<DateTime>                            $validFrom    The date when the item becomes valid.
 * @property-read SchemaTypeList<DateTime>                            $validThrough The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
 * @property-read SchemaTypeList<Number>                              $maxValue     The upper value of some characteristic or property.
 * @property-read SchemaTypeList<Text>                                $currency     The currency in which the monetary amount is expressed.
 * @property-read SchemaTypeList<Number>                              $minValue     The lower value of some characteristic or property.
 */
interface MonetaryAmount extends StructuredValue
{
}
