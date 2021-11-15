<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/TVSeries
 *
 * @property-read SchemaTypeList<CreativeWorkSeason> $seasons           A season in a media series.
 * @property-read SchemaTypeList<Person>             $actor             An actor, e.g. in tv, radio, movie, video games etc., or in an event. Actors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Integer>            $numberOfEpisodes  The number of episodes in this season or series.
 * @property-read SchemaTypeList<VideoObject>        $trailer           The trailer of a movie or tv/radio series, season, episode, etc.
 * @property-read SchemaTypeList<CreativeWorkSeason> $season            A season in a media series.
 * @property-read SchemaTypeList<Episode>            $episodes          An episode of a TV/radio series or season.
 * @property-read SchemaTypeList<Country>            $countryOfOrigin   The country of the principal offices of the production company or individual responsible for the movie or program.
 * @property-read SchemaTypeList<MusicGroup|Person>  $musicBy           The composer of the soundtrack.
 * @property-read SchemaTypeList<CreativeWorkSeason> $containsSeason    A season that is part of the media series.
 * @property-read SchemaTypeList<Person>             $directors         A director of e.g. tv, radio, movie, video games etc. content. Directors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Integer>            $numberOfSeasons   The number of seasons in this series.
 * @property-read SchemaTypeList<Person>             $director          A director of e.g. tv, radio, movie, video gaming etc. content, or of an event. Directors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Organization>       $productionCompany The production company or studio responsible for the item e.g. series, video game, episode etc.
 * @property-read SchemaTypeList<Person>             $actors            An actor, e.g. in tv, radio, movie, video games etc. Actors can be associated with individual items or with a series, episode, clip.
 * @property-read SchemaTypeList<Episode>            $episode           An episode of a tv, radio or game media within a series or season.
 */
interface TVSeries extends CreativeWorkSeries, CreativeWork
{
}
