<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/ReceiveAction
 *
 * @property-read SchemaTypeList<DeliveryMethod>               $deliveryMethod A sub property of instrument. The method of delivery.
 * @property-read SchemaTypeList<Audience|Organization|Person> $sender         A sub property of participant. The participant who is at the sending end of the action.
 */
interface ReceiveAction extends TransferAction
{
}
