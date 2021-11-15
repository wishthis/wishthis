<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/FollowAction
 *
 * @property-read SchemaTypeList<Organization|Person> $followee A sub property of object. The person or organization being followed.
 */
interface FollowAction extends InteractAction
{
}
