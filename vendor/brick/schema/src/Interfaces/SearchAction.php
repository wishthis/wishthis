<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/SearchAction
 *
 * @property-read SchemaTypeList<Text> $query A sub property of instrument. The query used on this action.
 */
interface SearchAction extends Action
{
}
