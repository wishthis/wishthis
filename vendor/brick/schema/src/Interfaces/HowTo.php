<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/HowTo
 *
 * @property-read SchemaTypeList<Duration>                                 $prepTime      The length of time it takes to prepare the items to be used in instructions or a direction, in ISO 8601 duration format.
 * @property-read SchemaTypeList<CreativeWork|ItemList|Text>               $steps         A single step item (as HowToStep, text, document, video, etc.) or a HowToSection (originally misnamed 'steps'; 'step' is preferred).
 * @property-read SchemaTypeList<HowToSection|CreativeWork|HowToStep|Text> $step          A single step item (as HowToStep, text, document, video, etc.) or a HowToSection.
 * @property-read SchemaTypeList<HowToTool|Text>                           $tool          A sub property of instrument. An object used (but not consumed) when performing instructions or a direction.
 * @property-read SchemaTypeList<Duration>                                 $performTime   The length of time it takes to perform instructions or a direction (not including time to prepare the supplies), in ISO 8601 duration format.
 * @property-read SchemaTypeList<Text|MonetaryAmount>                      $estimatedCost The estimated cost of the supply or supplies consumed when performing instructions.
 * @property-read SchemaTypeList<Duration>                                 $totalTime     The total time required to perform instructions or a direction (including time to prepare the supplies), in ISO 8601 duration format.
 * @property-read SchemaTypeList<Text|HowToSupply>                         $supply        A sub-property of instrument. A supply consumed when performing instructions or a direction.
 * @property-read SchemaTypeList<Text|QuantitativeValue>                   $yield         The quantity that results by performing instructions. For example, a paper airplane, 10 personalized candles.
 */
interface HowTo extends CreativeWork
{
}
