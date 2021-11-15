<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/PaymentChargeSpecification
 *
 * @property-read SchemaTypeList<PaymentMethod>  $appliesToPaymentMethod  The payment method(s) to which the payment charge specification applies.
 * @property-read SchemaTypeList<DeliveryMethod> $appliesToDeliveryMethod The delivery method(s) to which the delivery charge or payment charge specification applies.
 */
interface PaymentChargeSpecification extends PriceSpecification
{
}
