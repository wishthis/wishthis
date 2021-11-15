<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/PostalAddress
 *
 * @property-read SchemaTypeList<Text>         $postOfficeBoxNumber The post office box number for PO box addresses.
 * @property-read SchemaTypeList<Text>         $streetAddress       The street address. For example, 1600 Amphitheatre Pkwy.
 * @property-read SchemaTypeList<Text|Country> $addressCountry      The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
 * @property-read SchemaTypeList<Text>         $addressRegion       The region. For example, CA.
 * @property-read SchemaTypeList<Text>         $postalCode          The postal code. For example, 94043.
 * @property-read SchemaTypeList<Text>         $addressLocality     The locality. For example, Mountain View.
 */
interface PostalAddress extends ContactPoint
{
}
