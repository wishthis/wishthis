<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/TradeAction
 *
 * @property-read SchemaTypeList<PriceSpecification> $priceSpecification One or more detailed price specifications, indicating the unit price and delivery or payment charges.
 * @property-read SchemaTypeList<Text>               $priceCurrency      The currency of the price, or a price component when attached to PriceSpecification and its subtypes.
 * @property-read SchemaTypeList<Text|Number>        $price              The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.
 */
interface TradeAction extends Action
{
}
