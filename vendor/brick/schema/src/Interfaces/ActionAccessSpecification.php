<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Boolean;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ActionAccessSpecification
 *
 * @property-read SchemaTypeList<DateTime>                  $availabilityStarts   The beginning of the availability of the product or service included in the offer.
 * @property-read SchemaTypeList<MediaSubscription|Boolean> $requiresSubscription Indicates if use of the media require a subscription  (either paid or free). Allowed values are true or false (note that an earlier version had 'yes', 'no').
 * @property-read SchemaTypeList<Text|Thing>                $category             A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.
 * @property-read SchemaTypeList<DateTime>                  $availabilityEnds     The end of the availability of the product or service included in the offer.
 * @property-read SchemaTypeList<Offer>                     $expectsAcceptanceOf  An Offer which must be accepted before the user can perform the Action. For example, the user may need to buy a movie before being able to watch it.
 * @property-read SchemaTypeList<Place|GeoShape|Text>       $eligibleRegion       The ISO 3166-1 (ISO 3166-1 alpha-2) or ISO 3166-2 code, the place, or the GeoShape for the geo-political region(s) for which the offer or delivery charge specification is valid.
 */
interface ActionAccessSpecification extends Intangible
{
}
