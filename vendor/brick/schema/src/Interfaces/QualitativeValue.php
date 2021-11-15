<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/QualitativeValue
 *
 * @property-read SchemaTypeList<QualitativeValue>                                                             $greater            This ordering relation for qualitative values indicates that the subject is greater than the object.
 * @property-read SchemaTypeList<QualitativeValue>                                                             $equal              This ordering relation for qualitative values indicates that the subject is equal to the object.
 * @property-read SchemaTypeList<PropertyValue>                                                                $additionalProperty A property-value pair representing an additional characteristics of the entitity, e.g. a product feature or another characteristic for which there is no matching property in schema.org.
 * @property-read SchemaTypeList<QualitativeValue>                                                             $lesser             This ordering relation for qualitative values indicates that the subject is lesser than the object.
 * @property-read SchemaTypeList<QualitativeValue|QuantitativeValue|StructuredValue|PropertyValue|Enumeration> $valueReference     A pointer to a secondary value that provides additional information on the original value, e.g. a reference temperature.
 * @property-read SchemaTypeList<QualitativeValue>                                                             $nonEqual           This ordering relation for qualitative values indicates that the subject is not equal to the object.
 * @property-read SchemaTypeList<QualitativeValue>                                                             $lesserOrEqual      This ordering relation for qualitative values indicates that the subject is lesser than or equal to the object.
 * @property-read SchemaTypeList<QualitativeValue>                                                             $greaterOrEqual     This ordering relation for qualitative values indicates that the subject is greater than or equal to the object.
 */
interface QualitativeValue extends Enumeration
{
}
