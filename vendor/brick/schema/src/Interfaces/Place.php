<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/Place
 *
 * @property-read SchemaTypeList<Place>                        $geoWithin                        Represents a relationship between two geometries (or the places they represent), relating a geometry to one that contains it, i.e. it is inside (i.e. within) its interior. As defined in DE-9IM.
 * @property-read SchemaTypeList<Place>                        $geoContains                      Represents a relationship between two geometries (or the places they represent), relating a containing geometry to a contained geometry. "a contains b iff no points of b lie in the exterior of a, and at least one point of the interior of b lies in the interior of a". As defined in DE-9IM.
 * @property-read SchemaTypeList<ImageObject|Photograph>       $photo                            A photograph of this place.
 * @property-read SchemaTypeList<PostalAddress|Text>           $address                          Physical address of the item.
 * @property-read SchemaTypeList<OpeningHoursSpecification>    $openingHoursSpecification        The opening hours of a certain place.
 * @property-read SchemaTypeList<URL>                          $maps                             A URL to a map of the place.
 * @property-read SchemaTypeList<Text>                         $faxNumber                        The fax number.
 * @property-read SchemaTypeList<Place>                        $geoOverlaps                      Represents a relationship between two geometries (or the places they represent), relating a geometry to another that geospatially overlaps it, i.e. they have some but not all points in common. As defined in DE-9IM.
 * @property-read SchemaTypeList<Boolean>                      $smokingAllowed                   Indicates whether it is allowed to smoke in the place, e.g. in the restaurant, hotel or hotel room.
 * @property-read SchemaTypeList<Text>                         $globalLocationNumber             The Global Location Number (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.
 * @property-read SchemaTypeList<Integer>                      $maximumAttendeeCapacity          The total number of individuals that may attend an event or venue.
 * @property-read SchemaTypeList<Place>                        $geoCrosses                       Represents a relationship between two geometries (or the places they represent), relating a geometry to another that crosses it: "a crosses b: they have some but not all interior points in common, and the dimension of the intersection is less than that of at least one of them". As defined in DE-9IM.
 * @property-read SchemaTypeList<AggregateRating>              $aggregateRating                  The overall rating, based on a collection of reviews or ratings, of the item.
 * @property-read SchemaTypeList<Review>                       $reviews                          Review of the item.
 * @property-read SchemaTypeList<ImageObject|Photograph>       $photos                           Photographs of this place.
 * @property-read SchemaTypeList<URL>                          $map                              A URL to a map of the place.
 * @property-read SchemaTypeList<Text>                         $branchCode                       A short textual code (also called "store code") that uniquely identifies a place of business. The code is typically assigned by the parentOrganization and used in structured URLs.
 * @property-read SchemaTypeList<Map|URL>                      $hasMap                           A URL to a map of the place.
 * @property-read SchemaTypeList<PropertyValue>                $additionalProperty               A property-value pair representing an additional characteristics of the entitity, e.g. a product feature or another characteristic for which there is no matching property in schema.org.
 * @property-read SchemaTypeList<Event>                        $events                           Upcoming or past events associated with this place or organization.
 * @property-read SchemaTypeList<OpeningHoursSpecification>    $specialOpeningHoursSpecification The special opening hours of a certain place.
 * @property-read SchemaTypeList<LocationFeatureSpecification> $amenityFeature                   An amenity feature (e.g. a characteristic or service) of the Accommodation. This generic property does not make a statement about whether the feature is included in an offer for the main accommodation or available at extra costs.
 * @property-read SchemaTypeList<URL|ImageObject>              $logo                             An associated logo.
 * @property-read SchemaTypeList<Text>                         $telephone                        The telephone number.
 * @property-read SchemaTypeList<GeoShape|GeoCoordinates>      $geo                              The geo coordinates of the place.
 * @property-read SchemaTypeList<Place>                        $geoCovers                        Represents a relationship between two geometries (or the places they represent), relating a covering geometry to a covered geometry. "Every point of b is a point of (the interior or boundary of) a". As defined in DE-9IM.
 * @property-read SchemaTypeList<Place>                        $geoEquals                        Represents spatial relations in which two geometries (or the places they represent) are topologically equal, as defined in DE-9IM. "Two geometries are topologically equal if their interiors intersect and no part of the interior or boundary of one geometry intersects the exterior of the other" (a symmetric relationship)
 * @property-read SchemaTypeList<Place>                        $containedInPlace                 The basic containment relation between a place and one that contains it.
 * @property-read SchemaTypeList<Review>                       $review                           A review of the item.
 * @property-read SchemaTypeList<Place>                        $containedIn                      The basic containment relation between a place and one that contains it.
 * @property-read SchemaTypeList<Event>                        $event                            Upcoming or past event associated with this place, organization, or action.
 * @property-read SchemaTypeList<Place>                        $geoTouches                       Represents spatial relations in which two geometries (or the places they represent) touch: they have at least one boundary point in common, but no interior points." (a symmetric relationship, as defined in DE-9IM )
 * @property-read SchemaTypeList<Place>                        $containsPlace                    The basic containment relation between a place and another that it contains.
 * @property-read SchemaTypeList<Place>                        $geoDisjoint                      Represents spatial relations in which two geometries (or the places they represent) are topologically disjoint: they have no point in common. They form a set of disconnected geometries." (a symmetric relationship, as defined in DE-9IM)
 * @property-read SchemaTypeList<Text>                         $isicV4                           The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.
 * @property-read SchemaTypeList<Text>                         $slogan                           A slogan or motto associated with the item.
 * @property-read SchemaTypeList<Place>                        $geoIntersects                    Represents spatial relations in which two geometries (or the places they represent) have at least one point in common. As defined in DE-9IM.
 * @property-read SchemaTypeList<Boolean>                      $isAccessibleForFree              A flag to signal that the item, event, or place is accessible for free.
 * @property-read SchemaTypeList<Place>                        $geoCoveredBy                     Represents a relationship between two geometries (or the places they represent), relating a geometry to another that covers it. As defined in DE-9IM.
 * @property-read SchemaTypeList<Boolean>                      $publicAccess                     A flag to signal that the Place is open to public visitors.  If this property is omitted there is no assumed default boolean value
 */
interface Place extends Thing
{
}
