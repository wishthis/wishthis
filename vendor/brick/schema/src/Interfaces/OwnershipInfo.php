<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/OwnershipInfo
 *
 * @property-read SchemaTypeList<Product|Service>     $typeOfGood   The product that this structured value is referring to.
 * @property-read SchemaTypeList<DateTime>            $ownedFrom    The date and time of obtaining the product.
 * @property-read SchemaTypeList<DateTime>            $ownedThrough The date and time of giving up ownership on the product.
 * @property-read SchemaTypeList<Organization|Person> $acquiredFrom The organization or person from which the product was acquired.
 */
interface OwnershipInfo extends StructuredValue
{
}
