<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/MusicAlbum
 *
 * @property-read SchemaTypeList<MusicAlbumProductionType> $albumProductionType Classification of the album by it's type of content: soundtrack, live album, studio album, etc.
 * @property-read SchemaTypeList<MusicAlbumReleaseType>    $albumReleaseType    The kind of release which this album is: single, EP or album.
 * @property-read SchemaTypeList<MusicGroup|Person>        $byArtist            The artist that performed this album or recording.
 * @property-read SchemaTypeList<MusicRelease>             $albumRelease        A release of this album.
 */
interface MusicAlbum extends MusicPlaylist
{
}
