<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Message
 *
 * @property-read SchemaTypeList<DateTime>                                  $dateRead          The date/time at which the message has been read by the recipient if a single recipient exists.
 * @property-read SchemaTypeList<Person|Organization|ContactPoint>          $bccRecipient      A sub property of recipient. The recipient blind copied on a message.
 * @property-read SchemaTypeList<DateTime>                                  $dateSent          The date/time at which the message was sent.
 * @property-read SchemaTypeList<Organization|Person|ContactPoint>          $ccRecipient       A sub property of recipient. The recipient copied on a message.
 * @property-read SchemaTypeList<Audience|Person|Organization|ContactPoint> $recipient         A sub property of participant. The participant who is at the receiving end of the action.
 * @property-read SchemaTypeList<CreativeWork>                              $messageAttachment A CreativeWork attached to the message.
 * @property-read SchemaTypeList<DateTime>                                  $dateReceived      The date/time the message was received if a single recipient exists.
 * @property-read SchemaTypeList<Person|ContactPoint|Audience|Organization> $toRecipient       A sub property of recipient. The recipient who was directly sent the message.
 * @property-read SchemaTypeList<Audience|Organization|Person>              $sender            A sub property of participant. The participant who is at the sending end of the action.
 */
interface Message extends CreativeWork
{
}
