<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;

/**
 * http://schema.org/EmployeeRole
 *
 * @property-read SchemaTypeList<Text>                                     $salaryCurrency The currency (coded using ISO 4217 ) used for the main salary information in this job posting or for this employee.
 * @property-read SchemaTypeList<MonetaryAmount|PriceSpecification|Number> $baseSalary     The base salary of the job or of an employee in an EmployeeRole.
 */
interface EmployeeRole extends OrganizationRole
{
}
