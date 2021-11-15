<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\Date;

/**
 * http://schema.org/WebPage
 *
 * @property-read SchemaTypeList<URL|SpeakableSpecification> $speakable          Indicates sections of a Web page that are particularly 'speakable' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the 'speakable' property serves to indicate the parts most likely to be generally useful for speech.
 * @property-read SchemaTypeList<BreadcrumbList|Text>        $breadcrumb         A set of links that can help a user understand and navigate a website hierarchy.
 * @property-read SchemaTypeList<URL>                        $significantLink    One of the more significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.
 * @property-read SchemaTypeList<URL>                        $relatedLink        A link related to this web page, for example to other related web pages.
 * @property-read SchemaTypeList<Date>                       $lastReviewed       Date on which the content on this web page was last reviewed for accuracy and/or completeness.
 * @property-read SchemaTypeList<WebPageElement>             $mainContentOfPage  Indicates if this web page element is the main subject of the page.
 * @property-read SchemaTypeList<Person|Organization>        $reviewedBy         People or organizations that have reviewed the content on this web page for accuracy and/or completeness.
 * @property-read SchemaTypeList<ImageObject>                $primaryImageOfPage Indicates the main image on the page.
 * @property-read SchemaTypeList<URL>                        $significantLinks   The most significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.
 * @property-read SchemaTypeList<Specialty>                  $specialty          One of the domain specialities to which this web page's content applies.
 */
interface WebPage extends CreativeWork
{
}
