<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Text;
use Brick\Schema\DataType\DateTime;

/**
 * http://schema.org/Flight
 *
 * @property-read SchemaTypeList<Airport>             $arrivalAirport          The airport where the flight terminates.
 * @property-read SchemaTypeList<Text>                $arrivalGate             Identifier of the flight's arrival gate.
 * @property-read SchemaTypeList<Organization>        $carrier                 'carrier' is an out-dated term indicating the 'provider' for parcel delivery and flights.
 * @property-read SchemaTypeList<Airport>             $departureAirport        The airport where the flight originates.
 * @property-read SchemaTypeList<BoardingPolicyType>  $boardingPolicy          The type of boarding policy used by the airline (e.g. zone-based or group-based).
 * @property-read SchemaTypeList<Text|Vehicle>        $aircraft                The kind of aircraft (e.g., "Boeing 747").
 * @property-read SchemaTypeList<Person|Organization> $seller                  An entity which offers (sells / leases / lends / loans) the services / goods.  A seller may also be a provider.
 * @property-read SchemaTypeList<DateTime>            $webCheckinTime          The time when a passenger can check into the flight online.
 * @property-read SchemaTypeList<Text>                $mealService             Description of the meals that will be provided or available for purchase.
 * @property-read SchemaTypeList<Text>                $departureGate           Identifier of the flight's departure gate.
 * @property-read SchemaTypeList<Text>                $departureTerminal       Identifier of the flight's departure terminal.
 * @property-read SchemaTypeList<Text|Duration>       $estimatedFlightDuration The estimated time the flight will take.
 * @property-read SchemaTypeList<Distance|Text>       $flightDistance          The distance of the flight.
 * @property-read SchemaTypeList<Text>                $arrivalTerminal         Identifier of the flight's arrival terminal.
 * @property-read SchemaTypeList<Text>                $flightNumber            The unique identifier for a flight including the airline IATA code. For example, if describing United flight 110, where the IATA code for United is 'UA', the flightNumber is 'UA110'.
 */
interface Flight extends Trip
{
}
