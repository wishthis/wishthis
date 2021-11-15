<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/DeliveryEvent
 *
 * @property-read SchemaTypeList<DeliveryMethod> $hasDeliveryMethod Method used for delivery or shipping.
 * @property-read SchemaTypeList<DateTime>       $availableThrough  After this date, the item will no longer be available for pickup.
 * @property-read SchemaTypeList<DateTime>       $availableFrom     When the item is available for pickup from the store, locker, etc.
 * @property-read SchemaTypeList<Text>           $accessCode        Password, PIN, or access code needed for delivery (e.g. from a locker).
 */
interface DeliveryEvent extends Event
{
}
