<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/DigitalDocumentPermission
 *
 * @property-read SchemaTypeList<Person|ContactPoint|Audience|Organization> $grantee        The person, organization, contact point, or audience that has been granted this permission.
 * @property-read SchemaTypeList<DigitalDocumentPermissionType>             $permissionType The type of permission granted the person, organization, or audience.
 */
interface DigitalDocumentPermission extends Intangible
{
}
