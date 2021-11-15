<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/LendAction
 *
 * @property-read SchemaTypeList<Person> $borrower A sub property of participant. The person that borrows the object being lent.
 */
interface LendAction extends TransferAction
{
}
