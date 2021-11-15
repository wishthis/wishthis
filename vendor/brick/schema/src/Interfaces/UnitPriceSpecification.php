<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/UnitPriceSpecification
 *
 * @property-read SchemaTypeList<URL|Text>          $unitCode          The unit of measurement given using the UN/CEFACT Common Code (3 characters) or a URL. Other codes than the UN/CEFACT Common Code may be used with a prefix followed by a colon.
 * @property-read SchemaTypeList<Number>            $billingIncrement  This property specifies the minimal quantity and rounding increment that will be the basis for the billing. The unit of measurement is specified by the unitCode property.
 * @property-read SchemaTypeList<QuantitativeValue> $referenceQuantity The reference quantity for which a certain price applies, e.g. 1 EUR per 4 kWh of electricity. This property is a replacement for unitOfMeasurement for the advanced cases where the price does not relate to a standard unit.
 * @property-read SchemaTypeList<Text>              $unitText          A string or text indicating the unit of measurement. Useful if you cannot provide a standard unit code for
 * @property-read SchemaTypeList<Text>              $priceType         A short text or acronym indicating multiple price specifications for the same offer, e.g. SRP for the suggested retail price or INVOICE for the invoice price, mostly used in the car industry.
 */
interface UnitPriceSpecification extends PriceSpecification
{
}
