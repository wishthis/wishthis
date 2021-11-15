<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/AlignmentObject
 *
 * @property-read SchemaTypeList<Text> $targetDescription    The description of a node in an established educational framework.
 * @property-read SchemaTypeList<Text> $alignmentType        A category of alignment between the learning resource and the framework node. Recommended values include: 'assesses', 'teaches', 'requires', 'textComplexity', 'readingLevel', 'educationalSubject', and 'educationalLevel'.
 * @property-read SchemaTypeList<URL>  $targetUrl            The URL of a node in an established educational framework.
 * @property-read SchemaTypeList<Text> $targetName           The name of a node in an established educational framework.
 * @property-read SchemaTypeList<Text> $educationalFramework The framework to which the resource being described is aligned.
 */
interface AlignmentObject extends Intangible
{
}
