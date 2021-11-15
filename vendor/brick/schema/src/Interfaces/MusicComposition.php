<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/MusicComposition
 *
 * @property-read SchemaTypeList<Text>                $musicCompositionForm The type of composition (e.g. overture, sonata, symphony, etc.).
 * @property-read SchemaTypeList<CreativeWork>        $lyrics               The words in the song.
 * @property-read SchemaTypeList<MusicComposition>    $includedComposition  Smaller compositions included in this work (e.g. a movement in a symphony).
 * @property-read SchemaTypeList<Person>              $lyricist             The person who wrote the words.
 * @property-read SchemaTypeList<MusicRecording>      $recordedAs           An audio recording of the work.
 * @property-read SchemaTypeList<Event>               $firstPerformance     The date and place the work was first performed.
 * @property-read SchemaTypeList<MusicComposition>    $musicArrangement     An arrangement derived from the composition.
 * @property-read SchemaTypeList<Text>                $iswcCode             The International Standard Musical Work Code for the composition.
 * @property-read SchemaTypeList<Person|Organization> $composer             The person or organization who wrote a composition, or who is the composer of a work performed at some event.
 * @property-read SchemaTypeList<Text>                $musicalKey           The key, mode, or scale this composition uses.
 */
interface MusicComposition extends CreativeWork
{
}
