<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/LocalBusiness
 *
 * @property-read SchemaTypeList<Text>         $priceRange         The price range of the business, for example $$$.
 * @property-read SchemaTypeList<Organization> $branchOf           The larger organization that this local business is a branch of, if any. Not to be confused with (anatomical)branch.
 * @property-read SchemaTypeList<Text>         $paymentAccepted    Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
 * @property-read SchemaTypeList<Text>         $openingHours       The general opening hours for a business. Opening hours can be specified as a weekly time range, starting with days, then times per day. Multiple days can be listed with commas ',' separating each day. Day or time ranges are specified using a hyphen '-'.
 * @property-read SchemaTypeList<Text>         $currenciesAccepted The currency accepted.
 */
interface LocalBusiness extends Place, Organization
{
}
