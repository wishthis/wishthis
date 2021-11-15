<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/RentAction
 *
 * @property-read SchemaTypeList<Organization|Person> $landlord        A sub property of participant. The owner of the real estate property.
 * @property-read SchemaTypeList<RealEstateAgent>     $realEstateAgent A sub property of participant. The real estate agent involved in the action.
 */
interface RentAction extends TradeAction
{
}
