<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Boolean;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/Accommodation
 *
 * @property-read SchemaTypeList<QuantitativeValue>            $floorSize      The size of the accommodation, e.g. in square meter or squarefoot.
 * @property-read SchemaTypeList<Boolean|Text>                 $petsAllowed    Indicates whether pets are allowed to enter the accommodation or lodging business. More detailed information can be put in a text value.
 * @property-read SchemaTypeList<LocationFeatureSpecification> $amenityFeature An amenity feature (e.g. a characteristic or service) of the Accommodation. This generic property does not make a statement about whether the feature is included in an offer for the main accommodation or available at extra costs.
 * @property-read SchemaTypeList<Number|QuantitativeValue>     $numberOfRooms  The number of rooms (excluding bathrooms and closets) of the accommodation or lodging business.
 * @property-read SchemaTypeList<Text>                         $permittedUsage Indications regarding the permitted usage of the accommodation.
 */
interface Accommodation extends Place
{
}
