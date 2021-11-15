<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Occupation
 *
 * @property-read SchemaTypeList<AdministrativeArea>         $occupationLocation     The region/country for which this occupational description is appropriate. Note that educational requirements and qualifications can vary between jurisdictions.
 * @property-read SchemaTypeList<Text>                       $skills                 Skills required to fulfill this role or in this Occupation.
 * @property-read SchemaTypeList<Text>                       $responsibilities       Responsibilities associated with this role or Occupation.
 * @property-read SchemaTypeList<Text>                       $occupationalCategory   Category or categories describing the job. Use BLS O*NET-SOC taxonomy: http://www.onetcenter.org/taxonomy.html. Ideally includes textual label and formal code, with the property repeated for each applicable value.
 * @property-read SchemaTypeList<Text>                       $experienceRequirements Description of skills and experience needed for the position or Occupation.
 * @property-read SchemaTypeList<MonetaryAmountDistribution> $estimatedSalary        An estimated salary for a job posting or occupation, based on a variety of variables including, but not limited to industry, job title, and location. Estimated salaries  are often computed by outside organizations rather than the hiring organization, who may not have committed to the estimated value.
 */
interface Occupation extends Intangible
{
}
