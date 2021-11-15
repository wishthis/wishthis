<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/ProgramMembership
 *
 * @property-read SchemaTypeList<Text>                $membershipNumber    A unique identifier for the membership.
 * @property-read SchemaTypeList<Person|Organization> $members             A member of this organization.
 * @property-read SchemaTypeList<Organization|Person> $member              A member of an Organization or a ProgramMembership. Organizations can be members of organizations; ProgramMembership is typically for individuals.
 * @property-read SchemaTypeList<Text>                $programName         The program providing the membership.
 * @property-read SchemaTypeList<Organization>        $hostingOrganization The organization (airline, travelers' club, etc.) the membership is made with.
 */
interface ProgramMembership extends Intangible
{
}
