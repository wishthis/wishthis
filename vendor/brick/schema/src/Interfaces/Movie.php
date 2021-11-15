<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Movie
 *
 * @property-read SchemaTypeList<Person>            $actor             An actor, e.g. in tv, radio, movie, video games etc., or in an event. Actors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<VideoObject>       $trailer           The trailer of a movie or tv/radio series, season, episode, etc.
 * @property-read SchemaTypeList<Language|Text>     $subtitleLanguage  Languages in which subtitles/captions are available, in IETF BCP 47 standard format.
 * @property-read SchemaTypeList<Country>           $countryOfOrigin   The country of the principal offices of the production company or individual responsible for the movie or program.
 * @property-read SchemaTypeList<MusicGroup|Person> $musicBy           The composer of the soundtrack.
 * @property-read SchemaTypeList<Person>            $directors         A director of e.g. tv, radio, movie, video games etc. content. Directors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Person>            $director          A director of e.g. tv, radio, movie, video gaming etc. content, or of an event. Directors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Organization>      $productionCompany The production company or studio responsible for the item e.g. series, video game, episode etc.
 * @property-read SchemaTypeList<Duration>          $duration          The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
 * @property-read SchemaTypeList<Person>            $actors            An actor, e.g. in tv, radio, movie, video games etc. Actors can be associated with individual items or with a series, episode, clip.
 */
interface Movie extends CreativeWork
{
}
