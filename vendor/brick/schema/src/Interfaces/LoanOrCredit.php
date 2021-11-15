<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/LoanOrCredit
 *
 * @property-read SchemaTypeList<QuantitativeValue>     $loanTerm           The duration of the loan or credit agreement.
 * @property-read SchemaTypeList<Number|MonetaryAmount> $amount             The amount of money.
 * @property-read SchemaTypeList<Thing|Text>            $requiredCollateral Assets required to secure loan or credit repayments. It may take form of third party pledge, goods, financial instruments (cash, securities, etc.)
 * @property-read SchemaTypeList<Text>                  $currency           The currency in which the monetary amount is expressed.
 */
interface LoanOrCredit extends FinancialProduct
{
}
