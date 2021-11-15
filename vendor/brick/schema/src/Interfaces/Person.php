<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;

/**
 * http://schema.org/Person
 *
 * @property-read SchemaTypeList<ProgramMembership|Organization>       $memberOf             An Organization (or ProgramMembership) to which this Person or Organization belongs.
 * @property-read SchemaTypeList<PostalAddress|Text>                   $address              Physical address of the item.
 * @property-read SchemaTypeList<Person>                               $spouse               The person's spouse.
 * @property-read SchemaTypeList<Organization|Person>                  $funder               A person or organization that supports (sponsors) something through some kind of financial contribution.
 * @property-read SchemaTypeList<Person>                               $colleagues           A colleague of the person.
 * @property-read SchemaTypeList<Date>                                 $deathDate            Date of death.
 * @property-read SchemaTypeList<QuantitativeValue|Distance>           $height               The height of the item.
 * @property-read SchemaTypeList<Place|ContactPoint>                   $workLocation         A contact location for a person's place of work.
 * @property-read SchemaTypeList<Text>                                 $faxNumber            The fax number.
 * @property-read SchemaTypeList<Person>                               $children             A child of the person.
 * @property-read SchemaTypeList<Occupation>                           $hasOccupation        The Person's occupation. For past professions, use Role for expressing dates.
 * @property-read SchemaTypeList<Text>                                 $jobTitle             The job title of the person (for example, Financial Manager).
 * @property-read SchemaTypeList<OfferCatalog>                         $hasOfferCatalog      Indicates an OfferCatalog listing for this Organization, Person, or Service.
 * @property-read SchemaTypeList<Place>                                $deathPlace           The place where the person died.
 * @property-read SchemaTypeList<Text>                                 $globalLocationNumber The Global Location Number (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.
 * @property-read SchemaTypeList<Place>                                $birthPlace           The place where the person was born.
 * @property-read SchemaTypeList<GenderType|Text>                      $gender               Gender of the person. While http://schema.org/Male and http://schema.org/Female may be used, text strings are also acceptable for people who do not identify as a binary gender.
 * @property-read SchemaTypeList<Person>                               $parents              A parents of the person.
 * @property-read SchemaTypeList<Organization|EducationalOrganization> $alumniOf             An organization that the person is an alumni of.
 * @property-read SchemaTypeList<Place|ContactPoint>                   $homeLocation         A contact location for a person's residence.
 * @property-read SchemaTypeList<Text>                                 $duns                 The Dun & Bradstreet DUNS number for identifying an organization or business person.
 * @property-read SchemaTypeList<Text>                                 $taxID                The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.
 * @property-read SchemaTypeList<Text>                                 $award                An award won by or for this item.
 * @property-read SchemaTypeList<Date>                                 $birthDate            Date of birth.
 * @property-read SchemaTypeList<Offer>                                $makesOffer           A pointer to products or services offered by the organization or person.
 * @property-read SchemaTypeList<Text>                                 $givenName            Given name. In the U.S., the first name of a Person. This can be used along with familyName instead of the name property.
 * @property-read SchemaTypeList<ContactPoint>                         $contactPoints        A contact point for a person or organization.
 * @property-read SchemaTypeList<Text>                                 $awards               Awards won by or for this item.
 * @property-read SchemaTypeList<Text>                                 $familyName           Family name. In the U.S., the last name of an Person. This can be used along with givenName instead of the name property.
 * @property-read SchemaTypeList<Demand>                               $seeks                A pointer to products or services sought by the organization or person (demand).
 * @property-read SchemaTypeList<Person>                               $sibling              A sibling of the person.
 * @property-read SchemaTypeList<Event>                                $performerIn          Event that this person is a performer or participant in.
 * @property-read SchemaTypeList<Text>                                 $honorificPrefix      An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
 * @property-read SchemaTypeList<Text>                                 $additionalName       An additional name for a Person, can be used for a middle name.
 * @property-read SchemaTypeList<Person>                               $siblings             A sibling of the person.
 * @property-read SchemaTypeList<Text>                                 $telephone            The telephone number.
 * @property-read SchemaTypeList<Text>                                 $email                Email address.
 * @property-read SchemaTypeList<QuantitativeValue>                    $weight               The weight of the product or person.
 * @property-read SchemaTypeList<ContactPoint>                         $contactPoint         A contact point for a person or organization.
 * @property-read SchemaTypeList<URL|Person>                           $colleague            A colleague of the person.
 * @property-read SchemaTypeList<Text>                                 $naics                The North American Industry Classification System (NAICS) code for a particular organization or business person.
 * @property-read SchemaTypeList<Place>                                $hasPOS               Points-of-Sales operated by the organization or person.
 * @property-read SchemaTypeList<Person>                               $parent               A parent of this person.
 * @property-read SchemaTypeList<OwnershipInfo|Product>                $owns                 Products owned by the organization or person.
 * @property-read SchemaTypeList<Organization>                         $affiliation          An organization that this person is affiliated with. For example, a school/university, a club, or a team.
 * @property-read SchemaTypeList<CreativeWork|URL>                     $publishingPrinciples The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork.
 * @property-read SchemaTypeList<Organization|Person>                  $sponsor              A person or organization that supports a thing through a pledge, promise, or financial contribution. e.g. a sponsor of a Medical Study or a corporate sponsor of an event.
 * @property-read SchemaTypeList<Text>                                 $isicV4               The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.
 * @property-read SchemaTypeList<Organization|Brand>                   $brand                The brand(s) associated with a product or service, or the brand(s) maintained by an organization or business person.
 * @property-read SchemaTypeList<Text>                                 $honorificSuffix      An honorific suffix preceding a Person's name such as M.D. /PhD/MSCSW.
 * @property-read SchemaTypeList<MonetaryAmount|PriceSpecification>    $netWorth             The total financial value of the person as calculated by subtracting assets from liabilities.
 * @property-read SchemaTypeList<Text>                                 $vatID                The Value-added Tax ID of the organization or person.
 * @property-read SchemaTypeList<Country>                              $nationality          Nationality of the person.
 * @property-read SchemaTypeList<Person>                               $relatedTo            The most generic familial relation.
 * @property-read SchemaTypeList<Person>                               $follows              The most generic uni-directional social relation.
 * @property-read SchemaTypeList<Person>                               $knows                The most generic bi-directional social/work relation.
 * @property-read SchemaTypeList<Organization>                         $worksFor             Organizations that the person works for.
 */
interface Person extends Thing
{
}
