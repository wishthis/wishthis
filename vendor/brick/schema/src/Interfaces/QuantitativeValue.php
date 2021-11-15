<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/QuantitativeValue
 *
 * @property-read SchemaTypeList<URL|Text>                                                                     $unitCode           The unit of measurement given using the UN/CEFACT Common Code (3 characters) or a URL. Other codes than the UN/CEFACT Common Code may be used with a prefix followed by a colon.
 * @property-read SchemaTypeList<Number|Text|StructuredValue|Boolean>                                          $value              The value of the quantitative value or property value node.
 * @property-read SchemaTypeList<PropertyValue>                                                                $additionalProperty A property-value pair representing an additional characteristics of the entitity, e.g. a product feature or another characteristic for which there is no matching property in schema.org.
 * @property-read SchemaTypeList<QualitativeValue|QuantitativeValue|StructuredValue|PropertyValue|Enumeration> $valueReference     A pointer to a secondary value that provides additional information on the original value, e.g. a reference temperature.
 * @property-read SchemaTypeList<Number>                                                                       $maxValue           The upper value of some characteristic or property.
 * @property-read SchemaTypeList<Text>                                                                         $unitText           A string or text indicating the unit of measurement. Useful if you cannot provide a standard unit code for
 * @property-read SchemaTypeList<Number>                                                                       $minValue           The lower value of some characteristic or property.
 */
interface QuantitativeValue extends StructuredValue
{
}
