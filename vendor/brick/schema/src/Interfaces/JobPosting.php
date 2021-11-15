<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/JobPosting
 *
 * @property-read SchemaTypeList<Place>                                    $jobLocation            A (typically single) geographic location associated with the job position.
 * @property-read SchemaTypeList<Text>                                     $benefits               Description of benefits associated with the job.
 * @property-read SchemaTypeList<Text>                                     $incentiveCompensation  Description of bonus and commission compensation aspects of the job.
 * @property-read SchemaTypeList<Text>                                     $workHours              The typical working hours for this job (e.g. 1st shift, night shift, 8am-5pm).
 * @property-read SchemaTypeList<Text>                                     $salaryCurrency         The currency (coded using ISO 4217 ) used for the main salary information in this job posting or for this employee.
 * @property-read SchemaTypeList<Text>                                     $jobBenefits            Description of benefits associated with the job.
 * @property-read SchemaTypeList<Date>                                     $datePosted             Publication date for the job posting.
 * @property-read SchemaTypeList<Text>                                     $skills                 Skills required to fulfill this role or in this Occupation.
 * @property-read SchemaTypeList<Text>                                     $incentives             Description of bonus and commission compensation aspects of the job.
 * @property-read SchemaTypeList<Text>                                     $responsibilities       Responsibilities associated with this role or Occupation.
 * @property-read SchemaTypeList<MonetaryAmount|PriceSpecification|Number> $baseSalary             The base salary of the job or of an employee in an EmployeeRole.
 * @property-read SchemaTypeList<DateTime>                                 $validThrough           The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
 * @property-read SchemaTypeList<Organization>                             $hiringOrganization     Organization offering the job position.
 * @property-read SchemaTypeList<Occupation>                               $relevantOccupation     The Occupation for the JobPosting.
 * @property-read SchemaTypeList<Text>                                     $specialCommitments     Any special commitments associated with this job posting. Valid entries include VeteranCommit, MilitarySpouseCommit, etc.
 * @property-read SchemaTypeList<Text>                                     $occupationalCategory   Category or categories describing the job. Use BLS O*NET-SOC taxonomy: http://www.onetcenter.org/taxonomy.html. Ideally includes textual label and formal code, with the property repeated for each applicable value.
 * @property-read SchemaTypeList<Text>                                     $experienceRequirements Description of skills and experience needed for the position or Occupation.
 * @property-read SchemaTypeList<Text>                                     $employmentType         Type of employment (e.g. full-time, part-time, contract, temporary, seasonal, internship).
 * @property-read SchemaTypeList<MonetaryAmountDistribution>               $estimatedSalary        An estimated salary for a job posting or occupation, based on a variety of variables including, but not limited to industry, job title, and location. Estimated salaries  are often computed by outside organizations rather than the hiring organization, who may not have committed to the estimated value.
 * @property-read SchemaTypeList<Text>                                     $title                  The title of the job.
 * @property-read SchemaTypeList<Text>                                     $industry               The industry associated with the job position.
 */
interface JobPosting extends Intangible
{
}
