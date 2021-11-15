<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ServiceChannel
 *
 * @property-read SchemaTypeList<Service>       $providesService      The service provided by this channel.
 * @property-read SchemaTypeList<Place>         $serviceLocation      The location (e.g. civic structure, local business, etc.) where a person can go to access the service.
 * @property-read SchemaTypeList<PostalAddress> $servicePostalAddress The address for accessing the service by mail.
 * @property-read SchemaTypeList<Text|Language> $availableLanguage    A language someone may use with or at the item, service or place. Please use one of the language codes from the IETF BCP 47 standard. See also inLanguage
 * @property-read SchemaTypeList<ContactPoint>  $serviceSmsNumber     The number to access the service by text message.
 * @property-read SchemaTypeList<URL>           $serviceUrl           The website to access the service.
 * @property-read SchemaTypeList<ContactPoint>  $servicePhone         The phone number to use to access the service.
 * @property-read SchemaTypeList<Duration>      $processingTime       Estimated processing time for the service using this channel.
 */
interface ServiceChannel extends Intangible
{
}
