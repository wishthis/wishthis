<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/MusicRecording
 *
 * @property-read SchemaTypeList<MusicComposition>  $recordingOf The composition this track is a recording of.
 * @property-read SchemaTypeList<Text>              $isrcCode    The International Standard Recording Code for the recording.
 * @property-read SchemaTypeList<MusicAlbum>        $inAlbum     The album to which this recording belongs.
 * @property-read SchemaTypeList<MusicPlaylist>     $inPlaylist  The playlist to which this recording belongs.
 * @property-read SchemaTypeList<MusicGroup|Person> $byArtist    The artist that performed this album or recording.
 * @property-read SchemaTypeList<Duration>          $duration    The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
 */
interface MusicRecording extends CreativeWork
{
}
