<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/PropertyValueSpecification
 *
 * @property-read SchemaTypeList<Text|Thing> $defaultValue   The default value of the input.  For properties that expect a literal, the default is a literal value, for properties that expect an object, it's an ID reference to one of the current values.
 * @property-read SchemaTypeList<Text>       $valuePattern   Specifies a regular expression for testing literal values according to the HTML spec.
 * @property-read SchemaTypeList<Number>     $stepValue      The stepValue attribute indicates the granularity that is expected (and required) of the value in a PropertyValueSpecification.
 * @property-read SchemaTypeList<Number>     $valueMaxLength Specifies the allowed range for number of characters in a literal value.
 * @property-read SchemaTypeList<Boolean>    $readonlyValue  Whether or not a property is mutable.  Default is false. Specifying this for a property that also has a value makes it act similar to a "hidden" input in an HTML form.
 * @property-read SchemaTypeList<Number>     $valueMinLength Specifies the minimum allowed range for number of characters in a literal value.
 * @property-read SchemaTypeList<Number>     $maxValue       The upper value of some characteristic or property.
 * @property-read SchemaTypeList<Text>       $valueName      Indicates the name of the PropertyValueSpecification to be used in URL templates and form encoding in a manner analogous to HTML's input@name.
 * @property-read SchemaTypeList<Boolean>    $multipleValues Whether multiple values are allowed for the property.  Default is false.
 * @property-read SchemaTypeList<Number>     $minValue       The lower value of some characteristic or property.
 * @property-read SchemaTypeList<Boolean>    $valueRequired  Whether the property must be filled in to complete the action.  Default is false.
 */
interface PropertyValueSpecification extends Intangible
{
}
