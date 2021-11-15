<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/OrderAction
 *
 * @property-read SchemaTypeList<DeliveryMethod> $deliveryMethod A sub property of instrument. The method of delivery.
 */
interface OrderAction extends TradeAction
{
}
