<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/MusicGroup
 *
 * @property-read SchemaTypeList<MusicAlbum>              $albums           A collection of music albums.
 * @property-read SchemaTypeList<MusicRecording|ItemList> $track            A music recording (track)—usually a single song. If an ItemList is given, the list should contain items of type MusicRecording.
 * @property-read SchemaTypeList<Text|URL>                $genre            Genre of the creative work, broadcast channel or group.
 * @property-read SchemaTypeList<MusicRecording>          $tracks           A music recording (track)—usually a single song.
 * @property-read SchemaTypeList<Person>                  $musicGroupMember A member of a music group—for example, John, Paul, George, or Ringo.
 * @property-read SchemaTypeList<MusicAlbum>              $album            A music album.
 */
interface MusicGroup extends PerformingGroup
{
}
