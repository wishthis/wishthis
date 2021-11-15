<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/GeoCoordinates
 *
 * @property-read SchemaTypeList<PostalAddress|Text> $address        Physical address of the item.
 * @property-read SchemaTypeList<Text|Number>        $latitude       The latitude of a location. For example 37.42242 (WGS 84).
 * @property-read SchemaTypeList<Number|Text>        $longitude      The longitude of a location. For example -122.08585 (WGS 84).
 * @property-read SchemaTypeList<Text|Country>       $addressCountry The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
 * @property-read SchemaTypeList<Text>               $postalCode     The postal code. For example, 94043.
 * @property-read SchemaTypeList<Number|Text>        $elevation      The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNITOFMEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
 */
interface GeoCoordinates extends StructuredValue
{
}
