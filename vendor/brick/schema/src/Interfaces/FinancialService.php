<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/FinancialService
 *
 * @property-read SchemaTypeList<Text|URL> $feesAndCommissionsSpecification Description of fees, commissions, and other terms applied either to a class of financial product, or by a financial service organization.
 */
interface FinancialService extends LocalBusiness
{
}
