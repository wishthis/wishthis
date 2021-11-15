<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/Event
 *
 * @property-read SchemaTypeList<Thing>                    $about                     The subject matter of the content.
 * @property-read SchemaTypeList<Organization|Person>      $funder                    A person or organization that supports (sponsors) something through some kind of financial contribution.
 * @property-read SchemaTypeList<CreativeWork>             $workFeatured              A work featured in some event, e.g. exhibited in an ExhibitionEvent.
 * @property-read SchemaTypeList<Audience>                 $audience                  An intended audience, i.e. a group for whom something was created.
 * @property-read SchemaTypeList<Integer>                  $remainingAttendeeCapacity The number of attendee places for an event that remain unallocated.
 * @property-read SchemaTypeList<Person>                   $actor                     An actor, e.g. in tv, radio, movie, video games etc., or in an event. Actors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Organization|Person>      $performers                The main performer or performers of the event—for example, a presenter, musician, or actor.
 * @property-read SchemaTypeList<DateTime|Date>            $endDate                   The end date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<DateTime>                 $doorTime                  The time admission will commence.
 * @property-read SchemaTypeList<Organization|Person>      $contributor               A secondary contributor to the CreativeWork or Event.
 * @property-read SchemaTypeList<Integer>                  $maximumAttendeeCapacity   The total number of individuals that may attend an event or venue.
 * @property-read SchemaTypeList<Text>                     $typicalAgeRange           The typical expected age range, e.g. '7-9', '11-'.
 * @property-read SchemaTypeList<Person|Organization>      $organizer                 An organizer of an Event.
 * @property-read SchemaTypeList<Organization|Person>      $attendees                 A person attending the event.
 * @property-read SchemaTypeList<AggregateRating>          $aggregateRating           The overall rating, based on a collection of reviews or ratings, of the item.
 * @property-read SchemaTypeList<Event>                    $subEvent                  An Event that is part of this event. For example, a conference event includes many presentations, each of which is a subEvent of the conference.
 * @property-read SchemaTypeList<Event>                    $subEvents                 Events that are a part of this event. For example, a conference event includes many presentations, each subEvents of the conference.
 * @property-read SchemaTypeList<Offer>                    $offers                    An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<Person|Organization>      $attendee                  A person or organization attending the event.
 * @property-read SchemaTypeList<CreativeWork>             $workPerformed             A work performed in some event, for example a play performed in a TheaterEvent.
 * @property-read SchemaTypeList<EventStatusType>          $eventStatus               An eventStatus of an event represents its status; particularly useful when an event is cancelled or rescheduled.
 * @property-read SchemaTypeList<Date|DateTime>            $startDate                 The start date and time of the item (in ISO 8601 date format).
 * @property-read SchemaTypeList<Person>                   $director                  A director of e.g. tv, radio, movie, video gaming etc. content, or of an event. Directors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Event>                    $superEvent                An event that this event is a part of. For example, a collection of individual music performances might each have a music festival as their superEvent.
 * @property-read SchemaTypeList<Duration>                 $duration                  The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
 * @property-read SchemaTypeList<Person|Organization>      $translator                Organization or person who adapts a creative work to different languages, regional differences and technical requirements of a target market, or that translates during some event.
 * @property-read SchemaTypeList<Language|Text>            $inLanguage                The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. See also availableLanguage.
 * @property-read SchemaTypeList<Date>                     $previousStartDate         Used in conjunction with eventStatus for rescheduled or cancelled events. This property contains the previously scheduled start date. For rescheduled events, the startDate property should be used for the newly scheduled start date. In the (rare) case of an event that has been postponed and rescheduled multiple times, this field may be repeated.
 * @property-read SchemaTypeList<Review>                   $review                    A review of the item.
 * @property-read SchemaTypeList<Organization|Person>      $sponsor                   A person or organization that supports a thing through a pledge, promise, or financial contribution. e.g. a sponsor of a Medical Study or a corporate sponsor of an event.
 * @property-read SchemaTypeList<Text|Place|PostalAddress> $location                  The location of for example where the event is happening, an organization is located, or where an action takes place.
 * @property-read SchemaTypeList<CreativeWork>             $recordedIn                The CreativeWork that captured all or part of this Event.
 * @property-read SchemaTypeList<Person|Organization>      $composer                  The person or organization who wrote a composition, or who is the composer of a work performed at some event.
 * @property-read SchemaTypeList<Boolean>                  $isAccessibleForFree       A flag to signal that the item, event, or place is accessible for free.
 * @property-read SchemaTypeList<Organization|Person>      $performer                 A performer at the event—for example, a presenter, musician, musical group or actor.
 */
interface Event extends Thing
{
}
