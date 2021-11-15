<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/WarrantyPromise
 *
 * @property-read SchemaTypeList<QuantitativeValue> $durationOfWarranty The duration of the warranty promise. Common unitCode values are ANN for year, MON for months, or DAY for days.
 * @property-read SchemaTypeList<WarrantyScope>     $warrantyScope      The scope of the warranty promise.
 */
interface WarrantyPromise extends StructuredValue
{
}
