<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;

/**
 * http://schema.org/Invoice
 *
 * @property-read SchemaTypeList<Organization|Person>               $provider             The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller.
 * @property-read SchemaTypeList<DateTime>                          $paymentDue           The date that payment is due.
 * @property-read SchemaTypeList<Duration>                          $billingPeriod        The time interval used to compute the invoice.
 * @property-read SchemaTypeList<Text>                              $confirmationNumber   A number that confirms the given order or payment has been received.
 * @property-read SchemaTypeList<Date>                              $scheduledPaymentDate The date the invoice is scheduled to be paid.
 * @property-read SchemaTypeList<Organization|Person>               $customer             Party placing the order or paying the invoice.
 * @property-read SchemaTypeList<Text|Thing>                        $category             A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.
 * @property-read SchemaTypeList<PaymentStatusType|Text>            $paymentStatus        The status of payment; whether the invoice has been paid or not.
 * @property-read SchemaTypeList<Organization|Person>               $broker               An entity that arranges for an exchange between a buyer and a seller.  In most cases a broker never acquires or releases ownership of a product or service involved in an exchange.  If it is not clear whether an entity is a broker, seller, or buyer, the latter two terms are preferred.
 * @property-read SchemaTypeList<Order>                             $referencesOrder      The Order(s) related to this Invoice. One or more Orders may be combined into a single Invoice.
 * @property-read SchemaTypeList<PaymentMethod>                     $paymentMethod        The name of the credit card or other method of payment for the order.
 * @property-read SchemaTypeList<Text>                              $paymentMethodId      An identifier for the method of payment used (e.g. the last 4 digits of the credit card).
 * @property-read SchemaTypeList<PriceSpecification|MonetaryAmount> $minimumPaymentDue    The minimum payment required at this time.
 * @property-read SchemaTypeList<MonetaryAmount|PriceSpecification> $totalPaymentDue      The total amount due.
 * @property-read SchemaTypeList<Text>                              $accountId            The identifier for the account the payment will be applied to.
 * @property-read SchemaTypeList<DateTime>                          $paymentDueDate       The date that payment is due.
 */
interface Invoice extends Intangible
{
}
