<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/SendAction
 *
 * @property-read SchemaTypeList<Audience|Person|Organization|ContactPoint> $recipient      A sub property of participant. The participant who is at the receiving end of the action.
 * @property-read SchemaTypeList<DeliveryMethod>                            $deliveryMethod A sub property of instrument. The method of delivery.
 */
interface SendAction extends TransferAction
{
}
