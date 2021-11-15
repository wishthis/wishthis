<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/AuthorizeAction
 *
 * @property-read SchemaTypeList<Audience|Person|Organization|ContactPoint> $recipient A sub property of participant. The participant who is at the receiving end of the action.
 */
interface AuthorizeAction extends AllocateAction
{
}
