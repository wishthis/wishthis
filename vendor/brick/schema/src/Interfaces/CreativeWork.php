<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Boolean;

/**
 * http://schema.org/CreativeWork
 *
 * @property-read SchemaTypeList<Thing>                    $about                The subject matter of the content.
 * @property-read SchemaTypeList<Text>                     $accessibilitySummary A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed."
 * @property-read SchemaTypeList<AlignmentObject>          $educationalAlignment An alignment to an established educational framework.
 * @property-read SchemaTypeList<MediaObject>              $associatedMedia      A media object that encodes this CreativeWork. This property is a synonym for encoding.
 * @property-read SchemaTypeList<Organization|Person>      $funder               A person or organization that supports (sponsors) something through some kind of financial contribution.
 * @property-read SchemaTypeList<Clip|AudioObject>         $audio                An embedded audio object.
 * @property-read SchemaTypeList<CreativeWork>             $workExample          Example/instance/realization/derivation of the concept of this creative work. eg. The paperback edition, first edition, or eBook.
 * @property-read SchemaTypeList<Organization|Person>      $provider             The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller.
 * @property-read SchemaTypeList<MediaObject>              $encoding             A media object that encodes this CreativeWork. This property is a synonym for associatedMedia.
 * @property-read SchemaTypeList<Text>                     $interactivityType    The predominant mode of learning supported by the learning resource. Acceptable values are 'active', 'expositive', or 'mixed'.
 * @property-read SchemaTypeList<Person>                   $character            Fictional person connected with a creative work.
 * @property-read SchemaTypeList<Audience>                 $audience             An intended audience, i.e. a group for whom something was created.
 * @property-read SchemaTypeList<Organization>             $sourceOrganization   The Organization on whose behalf the creator was working.
 * @property-read SchemaTypeList<CreativeWork>             $isPartOf             Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.
 * @property-read SchemaTypeList<Clip|VideoObject>         $video                An embedded video object.
 * @property-read SchemaTypeList<Organization|Person>      $publisher            The publisher of the creative work.
 * @property-read SchemaTypeList<PublicationEvent>         $publication          A publication event associated with the item.
 * @property-read SchemaTypeList<Text>                     $text                 The textual content of this CreativeWork.
 * @property-read SchemaTypeList<Date>                     $expires              Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, or a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date.
 * @property-read SchemaTypeList<Organization|Person>      $contributor          A secondary contributor to the CreativeWork or Event.
 * @property-read SchemaTypeList<Text>                     $typicalAgeRange      The typical expected age range, e.g. '7-9', '11-'.
 * @property-read SchemaTypeList<Integer|Text>             $position             The position of an item in a series or sequence of items.
 * @property-read SchemaTypeList<PublicationEvent>         $releasedEvent        The place and time the release was issued, expressed as a PublicationEvent.
 * @property-read SchemaTypeList<Text>                     $educationalUse       The purpose of a work in the context of education; for example, 'assignment', 'group work'.
 * @property-read SchemaTypeList<Place>                    $contentLocation      The location depicted or described in the content. For example, the location in a photograph or painting.
 * @property-read SchemaTypeList<Text|URL>                 $schemaVersion        Indicates (by URL or string) a particular version of a schema used in some CreativeWork. For example, a document could declare a schemaVersion using an URL such as http://schema.org/version/2.0/ if precise indication of schema version was required by some application.
 * @property-read SchemaTypeList<Text>                     $accessibilityFeature Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility (WebSchemas wiki lists possible values).
 * @property-read SchemaTypeList<AggregateRating>          $aggregateRating      The overall rating, based on a collection of reviews or ratings, of the item.
 * @property-read SchemaTypeList<Place>                    $locationCreated      The location where the CreativeWork was created, which may not be the same as the location depicted in the CreativeWork.
 * @property-read SchemaTypeList<Text>                     $accessModeSufficient A list of single or combined accessModes that are sufficient to understand all the intellectual content of a resource. Expected values include:  auditory, tactile, textual, visual.
 * @property-read SchemaTypeList<URL|Text|DateTime>        $temporalCoverage     The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in ISO 8601 time interval format. In
 * @property-read SchemaTypeList<Person>                   $accountablePerson    Specifies the Person that is legally accountable for the CreativeWork.
 * @property-read SchemaTypeList<Place>                    $spatialCoverage      The spatialCoverage of a CreativeWork indicates the place(s) which are the focus of the content. It is a subproperty of
 * @property-read SchemaTypeList<Review>                   $reviews              Review of the item.
 * @property-read SchemaTypeList<Offer>                    $offers               An offer to provide this item—for example, an offer to sell a product, rent the DVD of a movie, perform a service, or give away tickets to an event.
 * @property-read SchemaTypeList<Person>                   $editor               Specifies the Person who edited the CreativeWork.
 * @property-read SchemaTypeList<URL>                      $discussionUrl        A link to the page containing the comments of the CreativeWork.
 * @property-read SchemaTypeList<Text>                     $award                An award won by or for this item.
 * @property-read SchemaTypeList<Organization|Person>      $copyrightHolder      The party holding the legal copyright to the CreativeWork.
 * @property-read SchemaTypeList<Text>                     $accessibilityHazard  A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3 (WebSchemas wiki lists possible values).
 * @property-read SchemaTypeList<Number>                   $copyrightYear        The year during which the claimed copyright for the CreativeWork was first asserted.
 * @property-read SchemaTypeList<Text>                     $awards               Awards won by or for this item.
 * @property-read SchemaTypeList<Event>                    $recordedAt           The Event where the CreativeWork was recorded. The CreativeWork may capture all or part of the event.
 * @property-read SchemaTypeList<Place>                    $spatial              The "spatial" property can be used in cases when more specific properties
 * @property-read SchemaTypeList<Integer>                  $commentCount         The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.
 * @property-read SchemaTypeList<URL|Text>                 $fileFormat           Media type, typically MIME format (see IANA site) of the content e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, 'encoding' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.
 * @property-read SchemaTypeList<Text|DateTime>            $temporal             The "temporal" property can be used in cases where more specific properties
 * @property-read SchemaTypeList<Text>                     $accessibilityAPI     Indicates that the resource is compatible with the referenced accessibility API (WebSchemas wiki lists possible values).
 * @property-read SchemaTypeList<InteractionCounter>       $interactionStatistic The number of interactions for the CreativeWork using the WebSite or SoftwareApplication. The most specific child type of InteractionCounter should be used.
 * @property-read SchemaTypeList<Rating|Text>              $contentRating        Official rating of a piece of content—for example,'MPAA PG-13'.
 * @property-read SchemaTypeList<Text>                     $learningResourceType The predominant type or kind characterizing the learning resource. For example, 'presentation', 'handout'.
 * @property-read SchemaTypeList<Text>                     $accessMode           The human sensory perceptual system or cognitive faculty through which a person may process or perceive information. Expected values include: auditory, tactile, textual, visual, colorDependent, chartOnVisual, chemOnVisual, diagramOnVisual, mathOnVisual, musicOnVisual, textOnVisual.
 * @property-read SchemaTypeList<Text|URL|Product>         $material             A material that something is made from, e.g. leather, wool, cotton, paper.
 * @property-read SchemaTypeList<Boolean>                  $isFamilyFriendly     Indicates whether this content is family friendly.
 * @property-read SchemaTypeList<CreativeWork>             $exampleOfWork        A creative work that this work is an example/instance/realization/derivation of.
 * @property-read SchemaTypeList<Number|Text>              $version              The version of the CreativeWork embodied by a specified resource.
 * @property-read SchemaTypeList<Date|DateTime>            $dateModified         The date on which the CreativeWork was most recently modified or when the item's entry was modified within a DataFeed.
 * @property-read SchemaTypeList<Text>                     $keywords             Keywords or tags used to describe this content. Multiple entries in a keywords list are typically delimited by commas.
 * @property-read SchemaTypeList<Text|URL>                 $genre                Genre of the creative work, broadcast channel or group.
 * @property-read SchemaTypeList<Thing>                    $mainEntity           Indicates the primary entity described in some page or other CreativeWork.
 * @property-read SchemaTypeList<Organization|Person>      $author               The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
 * @property-read SchemaTypeList<MediaObject>              $encodings            A media object that encodes this CreativeWork.
 * @property-read SchemaTypeList<CreativeWork|URL|Product> $isBasedOnUrl         A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.
 * @property-read SchemaTypeList<Text>                     $alternativeHeadline  A secondary title of the CreativeWork.
 * @property-read SchemaTypeList<Duration>                 $timeRequired         Approximate or typical time it takes to work with or through this learning resource for the typical intended target audience, e.g. 'PT30M', 'PT1H25M'.
 * @property-read SchemaTypeList<Person|Organization>      $translator           Organization or person who adapts a creative work to different languages, regional differences and technical requirements of a target market, or that translates during some event.
 * @property-read SchemaTypeList<URL>                      $thumbnailUrl         A thumbnail image relevant to the Thing.
 * @property-read SchemaTypeList<CreativeWork>             $hasPart              Indicates an item or CreativeWork that is part of this item, or CreativeWork (in some sense).
 * @property-read SchemaTypeList<Comment>                  $comment              Comments, typically from users.
 * @property-read SchemaTypeList<Language|Text>            $inLanguage           The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. See also availableLanguage.
 * @property-read SchemaTypeList<URL|Text>                 $encodingFormat       Media type typically expressed using a MIME format (see IANA site and MDN reference) e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc.).
 * @property-read SchemaTypeList<Review>                   $review               A review of the item.
 * @property-read SchemaTypeList<CreativeWork|URL>         $license              A license document that applies to this content, typically indicated by URL.
 * @property-read SchemaTypeList<Text>                     $accessibilityControl Identifies input methods that are sufficient to fully control the described resource (WebSchemas wiki lists possible values).
 * @property-read SchemaTypeList<CreativeWork|URL|Product> $isBasedOn            A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.
 * @property-read SchemaTypeList<Person|Organization>      $creator              The creator/author of this CreativeWork. This is the same as the Author property for CreativeWork.
 * @property-read SchemaTypeList<CreativeWork|URL>         $publishingPrinciples The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork.
 * @property-read SchemaTypeList<Organization|Person>      $sponsor              A person or organization that supports a thing through a pledge, promise, or financial contribution. e.g. a sponsor of a Medical Study or a corporate sponsor of an event.
 * @property-read SchemaTypeList<Person|Organization>      $producer             The person or organization who produced the work (e.g. music album, movie, tv/radio series etc.).
 * @property-read SchemaTypeList<Thing>                    $mentions             Indicates that the CreativeWork contains a reference to, but is not necessarily about a concept.
 * @property-read SchemaTypeList<DateTime|Date>            $dateCreated          The date on which the CreativeWork was created or the item was added to a DataFeed.
 * @property-read SchemaTypeList<Date>                     $datePublished        Date of first broadcast/publication.
 * @property-read SchemaTypeList<Boolean>                  $isAccessibleForFree  A flag to signal that the item, event, or place is accessible for free.
 * @property-read SchemaTypeList<Text>                     $headline             Headline of the article.
 * @property-read SchemaTypeList<CreativeWork|Text>        $citation             A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.
 */
interface CreativeWork extends Thing
{
}
