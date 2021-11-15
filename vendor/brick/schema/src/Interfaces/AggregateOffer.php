<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/AggregateOffer
 *
 * @property-read SchemaTypeList<Offer>       $offers     An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<Integer>     $offerCount The number of offers for the product.
 * @property-read SchemaTypeList<Text|Number> $lowPrice   The lowest price of all offers available.
 * @property-read SchemaTypeList<Number|Text> $highPrice  The highest price of all offers available.
 */
interface AggregateOffer extends Offer
{
}
