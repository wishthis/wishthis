<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/InvestmentOrDeposit
 *
 * @property-read SchemaTypeList<Number|MonetaryAmount> $amount The amount of money.
 */
interface InvestmentOrDeposit extends FinancialProduct
{
}
