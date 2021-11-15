<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/PriceSpecification
 *
 * @property-read SchemaTypeList<Number>             $minPrice                  The lowest price if the price is a range.
 * @property-read SchemaTypeList<PriceSpecification> $eligibleTransactionVolume The transaction volume, in a monetary unit, for which the offer or price specification is valid, e.g. for indicating a minimal purchasing volume, to express free shipping above a certain order volume, or to limit the acceptance of credit cards to purchases to a certain minimal amount.
 * @property-read SchemaTypeList<Number>             $maxPrice                  The highest price if the price is a range.
 * @property-read SchemaTypeList<Text>               $priceCurrency             The currency of the price, or a price component when attached to PriceSpecification and its subtypes.
 * @property-read SchemaTypeList<QuantitativeValue>  $eligibleQuantity          The interval and unit of measurement of ordering quantities for which the offer or price specification is valid. This allows e.g. specifying that a certain freight charge is valid only for a certain quantity.
 * @property-read SchemaTypeList<DateTime>           $validFrom                 The date when the item becomes valid.
 * @property-read SchemaTypeList<DateTime>           $validThrough              The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
 * @property-read SchemaTypeList<Text|Number>        $price                     The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.
 * @property-read SchemaTypeList<Boolean>            $valueAddedTaxIncluded     Specifies whether the applicable value-added tax (VAT) is included in the price specification or not.
 */
interface PriceSpecification extends StructuredValue
{
}
