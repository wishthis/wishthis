<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ContactPoint
 *
 * @property-read SchemaTypeList<Place|GeoShape|AdministrativeArea>      $serviceArea       The geographic area where the service is provided.
 * @property-read SchemaTypeList<AdministrativeArea|GeoShape|Place|Text> $areaServed        The geographic area where a service or offered item is provided.
 * @property-read SchemaTypeList<Text>                                   $faxNumber         The fax number.
 * @property-read SchemaTypeList<OpeningHoursSpecification>              $hoursAvailable    The hours during which this service or contact is available.
 * @property-read SchemaTypeList<ContactPointOption>                     $contactOption     An option available on this contact point (e.g. a toll-free number or support for hearing-impaired callers).
 * @property-read SchemaTypeList<Text|Language>                          $availableLanguage A language someone may use with or at the item, service or place. Please use one of the language codes from the IETF BCP 47 standard. See also inLanguage
 * @property-read SchemaTypeList<Text>                                   $telephone         The telephone number.
 * @property-read SchemaTypeList<Text>                                   $email             Email address.
 * @property-read SchemaTypeList<Text>                                   $contactType       A person or organization can have different contact points, for different purposes. For example, a sales contact point, a PR contact point and so on. This property is used to specify the kind of contact point.
 * @property-read SchemaTypeList<Text|Product>                           $productSupported  The product or service this support contact point is related to (such as product support for a particular product line). This can be a specific product or product line (e.g. "iPhone") or a general category of products or services (e.g. "smartphones").
 */
interface ContactPoint extends StructuredValue
{
}
