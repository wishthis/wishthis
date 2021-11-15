<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/WriteAction
 *
 * @property-read SchemaTypeList<Language|Text> $inLanguage The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. See also availableLanguage.
 * @property-read SchemaTypeList<Language>      $language   A sub property of instrument. The language used on this action.
 */
interface WriteAction extends CreateAction
{
}
