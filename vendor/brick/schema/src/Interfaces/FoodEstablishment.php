<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/FoodEstablishment
 *
 * @property-read SchemaTypeList<Text>             $servesCuisine       The cuisine of the restaurant.
 * @property-read SchemaTypeList<URL|Menu|Text>    $hasMenu             Either the actual menu as a structured representation, as text, or a URL of the menu.
 * @property-read SchemaTypeList<Menu|Text|URL>    $menu                Either the actual menu as a structured representation, as text, or a URL of the menu.
 * @property-read SchemaTypeList<Rating>           $starRating          An official rating for a lodging business or food establishment, e.g. from national associations or standards bodies. Use the author property to indicate the rating organization, e.g. as an Organization with name such as (e.g. HOTREC, DEHOGA, WHR, or Hotelstars).
 * @property-read SchemaTypeList<URL|Text|Boolean> $acceptsReservations Indicates whether a FoodEstablishment accepts reservations. Values can be Boolean, an URL at which reservations can be made or (for backwards compatibility) the strings Yes or No.
 */
interface FoodEstablishment extends LocalBusiness
{
}
