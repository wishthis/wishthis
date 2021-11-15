<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/AskAction
 *
 * @property-read SchemaTypeList<Question> $question A sub property of object. A question.
 */
interface AskAction extends CommunicateAction
{
}
