<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/QuantitativeValueDistribution
 *
 * @property-read SchemaTypeList<Number>   $percentile10 The 10th percentile value.
 * @property-read SchemaTypeList<Number>   $percentile25 The 25th percentile value.
 * @property-read SchemaTypeList<Number>   $median       The median value.
 * @property-read SchemaTypeList<Duration> $duration     The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
 * @property-read SchemaTypeList<Number>   $percentile90 The 90th percentile value.
 * @property-read SchemaTypeList<Number>   $percentile75 The 75th percentile value.
 */
interface QuantitativeValueDistribution extends StructuredValue
{
}
