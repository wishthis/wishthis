<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/OrderItem
 *
 * @property-read SchemaTypeList<OrderStatus>               $orderItemStatus The current status of the order item.
 * @property-read SchemaTypeList<Number>                    $orderQuantity   The number of the item ordered. If the property is not set, assume the quantity is one.
 * @property-read SchemaTypeList<Product|Service|OrderItem> $orderedItem     The item ordered.
 * @property-read SchemaTypeList<ParcelDelivery>            $orderDelivery   The delivery of the parcel related to this order or order item.
 * @property-read SchemaTypeList<Text>                      $orderItemNumber The identifier of the order item.
 */
interface OrderItem extends Intangible
{
}
