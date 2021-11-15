<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/BuyAction
 *
 * @property-read SchemaTypeList<Organization|Person> $vendor          'vendor' is an earlier term for 'seller'.
 * @property-read SchemaTypeList<Person|Organization> $seller          An entity which offers (sells / leases / lends / loans) the services / goods.  A seller may also be a provider.
 * @property-read SchemaTypeList<WarrantyPromise>     $warrantyPromise The warranty promise(s) included in the offer.
 */
interface BuyAction extends TradeAction
{
}
