<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Reservation
 *
 * @property-read SchemaTypeList<Organization|Person>            $provider              The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller.
 * @property-read SchemaTypeList<ProgramMembership>              $programMembershipUsed Any membership in a frequent flyer, hotel loyalty program, etc. being applied to the reservation.
 * @property-read SchemaTypeList<ReservationStatusType>          $reservationStatus     The current status of the reservation.
 * @property-read SchemaTypeList<Person|Organization>            $underName             The person or organization the reservation or ticket is for.
 * @property-read SchemaTypeList<Organization|Person>            $bookingAgent          'bookingAgent' is an out-dated term indicating a 'broker' that serves as a booking agent.
 * @property-read SchemaTypeList<PriceSpecification|Number|Text> $totalPrice            The total price for the reservation or ticket, including applicable taxes, shipping, etc.
 * @property-read SchemaTypeList<Thing>                          $reservationFor        The thing -- flight, event, restaurant,etc. being reserved.
 * @property-read SchemaTypeList<Text>                           $priceCurrency         The currency of the price, or a price component when attached to PriceSpecification and its subtypes.
 * @property-read SchemaTypeList<Organization|Person>            $broker                An entity that arranges for an exchange between a buyer and a seller.  In most cases a broker never acquires or releases ownership of a product or service involved in an exchange.  If it is not clear whether an entity is a broker, seller, or buyer, the latter two terms are preferred.
 * @property-read SchemaTypeList<DateTime>                       $modifiedTime          The date and time the reservation was modified.
 * @property-read SchemaTypeList<DateTime>                       $bookingTime           The date and time the reservation was booked.
 * @property-read SchemaTypeList<Text>                           $reservationId         A unique identifier for the reservation.
 * @property-read SchemaTypeList<Ticket>                         $reservedTicket        A ticket associated with the reservation.
 */
interface Reservation extends Intangible
{
}
