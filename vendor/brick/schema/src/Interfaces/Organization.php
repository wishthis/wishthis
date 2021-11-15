<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;

/**
 * http://schema.org/Organization
 *
 * @property-read SchemaTypeList<Place|GeoShape|AdministrativeArea>      $serviceArea          The geographic area where the service is provided.
 * @property-read SchemaTypeList<ProgramMembership|Organization>         $memberOf             An Organization (or ProgramMembership) to which this Person or Organization belongs.
 * @property-read SchemaTypeList<PostalAddress|Text>                     $address              Physical address of the item.
 * @property-read SchemaTypeList<Organization|Person>                    $funder               A person or organization that supports (sponsors) something through some kind of financial contribution.
 * @property-read SchemaTypeList<AdministrativeArea|GeoShape|Place|Text> $areaServed           The geographic area where a service or offered item is provided.
 * @property-read SchemaTypeList<Text>                                   $faxNumber            The fax number.
 * @property-read SchemaTypeList<Organization>                           $subOrganization      A relationship between two organizations where the first includes the second, e.g., as a subsidiary. See also: the more specific 'department' property.
 * @property-read SchemaTypeList<OfferCatalog>                           $hasOfferCatalog      Indicates an OfferCatalog listing for this Organization, Person, or Service.
 * @property-read SchemaTypeList<Text>                                   $globalLocationNumber The Global Location Number (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.
 * @property-read SchemaTypeList<Person|Organization>                    $members              A member of this organization.
 * @property-read SchemaTypeList<AggregateRating>                        $aggregateRating      The overall rating, based on a collection of reviews or ratings, of the item.
 * @property-read SchemaTypeList<Text>                                   $duns                 The Dun & Bradstreet DUNS number for identifying an organization or business person.
 * @property-read SchemaTypeList<Text>                                   $taxID                The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.
 * @property-read SchemaTypeList<Review>                                 $reviews              Review of the item.
 * @property-read SchemaTypeList<Text>                                   $award                An award won by or for this item.
 * @property-read SchemaTypeList<Offer>                                  $makesOffer           A pointer to products or services offered by the organization or person.
 * @property-read SchemaTypeList<ContactPoint>                           $contactPoints        A contact point for a person or organization.
 * @property-read SchemaTypeList<Text>                                   $awards               Awards won by or for this item.
 * @property-read SchemaTypeList<Demand>                                 $seeks                A pointer to products or services sought by the organization or person (demand).
 * @property-read SchemaTypeList<Organization|Person>                    $member               A member of an Organization or a ProgramMembership. Organizations can be members of organizations; ProgramMembership is typically for individuals.
 * @property-read SchemaTypeList<Person>                                 $founders             A person who founded this organization.
 * @property-read SchemaTypeList<Person>                                 $alumni               Alumni of an organization.
 * @property-read SchemaTypeList<Date>                                   $dissolutionDate      The date that this organization was dissolved.
 * @property-read SchemaTypeList<Event>                                  $events               Upcoming or past events associated with this place or organization.
 * @property-read SchemaTypeList<URL|ImageObject>                        $logo                 An associated logo.
 * @property-read SchemaTypeList<Person>                                 $employees            People working for this organization.
 * @property-read SchemaTypeList<Text>                                   $telephone            The telephone number.
 * @property-read SchemaTypeList<Text>                                   $email                Email address.
 * @property-read SchemaTypeList<Organization>                           $department           A relationship between an organization and a department of that organization, also described as an organization (allowing different urls, logos, opening hours). For example: a store with a pharmacy, or a bakery with a cafe.
 * @property-read SchemaTypeList<ContactPoint>                           $contactPoint         A contact point for a person or organization.
 * @property-read SchemaTypeList<Organization>                           $parentOrganization   The larger organization that this organization is a subOrganization of, if any.
 * @property-read SchemaTypeList<Text>                                   $legalName            The official name of the organization, e.g. the registered company name.
 * @property-read SchemaTypeList<Date>                                   $foundingDate         The date that this organization was founded.
 * @property-read SchemaTypeList<Person>                                 $employee             Someone working for this organization.
 * @property-read SchemaTypeList<QuantitativeValue>                      $numberOfEmployees    The number of employees in an organization e.g. business.
 * @property-read SchemaTypeList<Text>                                   $naics                The North American Industry Classification System (NAICS) code for a particular organization or business person.
 * @property-read SchemaTypeList<Place>                                  $hasPOS               Points-of-Sales operated by the organization or person.
 * @property-read SchemaTypeList<Review>                                 $review               A review of the item.
 * @property-read SchemaTypeList<Place>                                  $foundingLocation     The place where the Organization was founded.
 * @property-read SchemaTypeList<OwnershipInfo|Product>                  $owns                 Products owned by the organization or person.
 * @property-read SchemaTypeList<Event>                                  $event                Upcoming or past event associated with this place, organization, or action.
 * @property-read SchemaTypeList<Person>                                 $founder              A person who founded this organization.
 * @property-read SchemaTypeList<CreativeWork|URL>                       $publishingPrinciples The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork.
 * @property-read SchemaTypeList<Organization|Person>                    $sponsor              A person or organization that supports a thing through a pledge, promise, or financial contribution. e.g. a sponsor of a Medical Study or a corporate sponsor of an event.
 * @property-read SchemaTypeList<Text>                                   $isicV4               The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.
 * @property-read SchemaTypeList<Text>                                   $slogan               A slogan or motto associated with the item.
 * @property-read SchemaTypeList<Text|Place|PostalAddress>               $location             The location of for example where the event is happening, an organization is located, or where an action takes place.
 * @property-read SchemaTypeList<Organization|Brand>                     $brand                The brand(s) associated with a product or service, or the brand(s) maintained by an organization or business person.
 * @property-read SchemaTypeList<Text>                                   $vatID                The Value-added Tax ID of the organization or person.
 * @property-read SchemaTypeList<Text>                                   $leiCode              An organization identifier that uniquely identifies a legal entity as defined in ISO 17442.
 */
interface Organization extends Thing
{
}
