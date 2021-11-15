<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/CommunicateAction
 *
 * @property-read SchemaTypeList<Thing>                                     $about      The subject matter of the content.
 * @property-read SchemaTypeList<Audience|Person|Organization|ContactPoint> $recipient  A sub property of participant. The participant who is at the receiving end of the action.
 * @property-read SchemaTypeList<Language|Text>                             $inLanguage The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. See also availableLanguage.
 * @property-read SchemaTypeList<Language>                                  $language   A sub property of instrument. The language used on this action.
 */
interface CommunicateAction extends InteractAction
{
}
