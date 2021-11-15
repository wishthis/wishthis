<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/InteractionCounter
 *
 * @property-read SchemaTypeList<SoftwareApplication|WebSite> $interactionService   The WebSite or SoftwareApplication where the interactions took place.
 * @property-read SchemaTypeList<Integer>                     $userInteractionCount The number of interactions for the CreativeWork using the WebSite or SoftwareApplication.
 * @property-read SchemaTypeList<Action>                      $interactionType      The Action representing the type of interaction. For up votes, +1s, etc. use LikeAction. For down votes use DislikeAction. Otherwise, use the most specific Action.
 */
interface InteractionCounter extends StructuredValue
{
}
