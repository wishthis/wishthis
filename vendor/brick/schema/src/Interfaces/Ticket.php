<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Ticket
 *
 * @property-read SchemaTypeList<Person|Organization>            $underName     The person or organization the reservation or ticket is for.
 * @property-read SchemaTypeList<PriceSpecification|Number|Text> $totalPrice    The total price for the reservation or ticket, including applicable taxes, shipping, etc.
 * @property-read SchemaTypeList<Text>                           $priceCurrency The currency of the price, or a price component when attached to PriceSpecification and its subtypes.
 * @property-read SchemaTypeList<Text>                           $ticketNumber  The unique identifier for the ticket.
 * @property-read SchemaTypeList<Organization>                   $issuedBy      The organization issuing the ticket or permit.
 * @property-read SchemaTypeList<Text|URL>                       $ticketToken   Reference to an asset (e.g., Barcode, QR code image or PDF) usable for entrance.
 * @property-read SchemaTypeList<Seat>                           $ticketedSeat  The seat associated with the ticket.
 * @property-read SchemaTypeList<DateTime>                       $dateIssued    The date the ticket was issued.
 */
interface Ticket extends Intangible
{
}
