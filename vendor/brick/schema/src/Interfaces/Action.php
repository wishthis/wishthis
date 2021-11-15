<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Action
 *
 * @property-read SchemaTypeList<Thing>                    $result       The result produced in the action. e.g. John wrote a book.
 * @property-read SchemaTypeList<DateTime>                 $startTime    The startTime of something. For a reserved event or service (e.g. FoodEstablishmentReservation), the time that it is expected to start. For actions that span a period of time, when the action was performed. e.g. John wrote a book from January to December. For media, including audio and video, it's the time offset of the start of a clip within a larger file.
 * @property-read SchemaTypeList<ActionStatusType>         $actionStatus Indicates the current disposition of the Action.
 * @property-read SchemaTypeList<EntryPoint>               $target       Indicates a target EntryPoint for an Action.
 * @property-read SchemaTypeList<Person|Organization>      $agent        The direct performer or driver of the action (animate or inanimate). e.g. John wrote a book.
 * @property-read SchemaTypeList<DateTime>                 $endTime      The endTime of something. For a reserved event or service (e.g. FoodEstablishmentReservation), the time that it is expected to end. For actions that span a period of time, when the action was performed. e.g. John wrote a book from January to December. For media, including audio and video, it's the time offset of the end of a clip within a larger file.
 * @property-read SchemaTypeList<Thing>                    $instrument   The object that helped the agent perform the action. e.g. John wrote a book with a pen.
 * @property-read SchemaTypeList<Organization|Person>      $participant  Other co-agents that participated in the action indirectly. e.g. John wrote a book with Steve.
 * @property-read SchemaTypeList<Thing>                    $object       The object upon which the action is carried out, whose state is kept intact or changed. Also known as the semantic roles patient, affected or undergoer (which change their state) or theme (which doesn't). e.g. John read a book.
 * @property-read SchemaTypeList<Thing>                    $error        For failed actions, more information on the cause of the failure.
 * @property-read SchemaTypeList<Text|Place|PostalAddress> $location     The location of for example where the event is happening, an organization is located, or where an action takes place.
 */
interface Action extends Thing
{
}
