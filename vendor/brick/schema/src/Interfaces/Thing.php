<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Thing
 *
 * @property-read SchemaTypeList<URL>                    $sameAs                    URL of a reference Web page that unambiguously indicates the item's identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or official website.
 * @property-read SchemaTypeList<URL>                    $url                       URL of the item.
 * @property-read SchemaTypeList<ImageObject|URL>        $image                     An image of the item. This can be a URL or a fully described ImageObject.
 * @property-read SchemaTypeList<URL>                    $additionalType            An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. In RDFa syntax, it is better to use the native RDFa syntax - the 'typeof' attribute - for multiple types. Schema.org tools may have only weaker understanding of extra types, in particular those defined externally.
 * @property-read SchemaTypeList<Text>                   $name                      The name of the item.
 * @property-read SchemaTypeList<Text|URL|PropertyValue> $identifier                The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See background notes for more details.
 * @property-read SchemaTypeList<CreativeWork|Event>     $subjectOf                 A CreativeWork or Event about this Thing..
 * @property-read SchemaTypeList<CreativeWork|URL>       $mainEntityOfPage          Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See background notes for details.
 * @property-read SchemaTypeList<Action>                 $potentialAction           Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role.
 * @property-read SchemaTypeList<Text>                   $description               A description of the item.
 * @property-read SchemaTypeList<Text>                   $disambiguatingDescription A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.
 * @property-read SchemaTypeList<Text>                   $alternateName             An alias for the item.
 */
interface Thing
{
}
