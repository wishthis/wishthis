<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Trip
 *
 * @property-read SchemaTypeList<Organization|Person> $provider      The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller.
 * @property-read SchemaTypeList<Offer>               $offers        An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<DateTime>            $arrivalTime   The expected arrival time.
 * @property-read SchemaTypeList<DateTime>            $departureTime The expected departure time.
 */
interface Trip extends Intangible
{
}
