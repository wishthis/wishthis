<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;
use Brick\Schema\DataType\Date;
use Brick\Schema\DataType\Number;
use Brick\Schema\DataType\Text;

/**
 * http://schema.org/Vehicle
 *
 * @property-read SchemaTypeList<Date>                              $productionDate              The date of production of the item, e.g. vehicle.
 * @property-read SchemaTypeList<Number|QuantitativeValue>          $numberOfForwardGears        The total number of forward gears available for the transmission system of the vehicle.
 * @property-read SchemaTypeList<QuantitativeValue>                 $mileageFromOdometer         The total distance travelled by the particular vehicle since its initial production, as read from its odometer.
 * @property-read SchemaTypeList<QuantitativeValue>                 $cargoVolume                 The available volume for cargo or luggage. For automobiles, this is usually the trunk volume.
 * @property-read SchemaTypeList<Text>                              $vehicleInteriorColor        The color or color combination of the interior of the vehicle.
 * @property-read SchemaTypeList<SteeringPositionValue>             $steeringPosition            The position of the steering wheel or similar device (mostly for cars).
 * @property-read SchemaTypeList<EngineSpecification>               $vehicleEngine               Information about the engine or engines of the vehicle.
 * @property-read SchemaTypeList<Date>                              $vehicleModelDate            The release date of a vehicle model (often used to differentiate versions of the same make and model).
 * @property-read SchemaTypeList<QuantitativeValue|Number>          $numberOfDoors               The number of doors.
 * @property-read SchemaTypeList<Text>                              $vehicleConfiguration        A short text indicating the configuration of the vehicle, e.g. '5dr hatchback ST 2.5 MT 225 hp' or 'limited edition'.
 * @property-read SchemaTypeList<QualitativeValue|URL|Text>         $fuelType                    The type of fuel suitable for the engine or engines of the vehicle. If the vehicle has only one engine, this property can be attached directly to the vehicle.
 * @property-read SchemaTypeList<Text>                              $vehicleIdentificationNumber The Vehicle Identification Number (VIN) is a unique serial number used by the automotive industry to identify individual motor vehicles.
 * @property-read SchemaTypeList<QuantitativeValue>                 $fuelConsumption             The amount of fuel consumed for traveling a particular distance or temporal duration with the given vehicle (e.g. liters per 100 km).
 * @property-read SchemaTypeList<QuantitativeValue|Number>          $numberOfPreviousOwners      The number of owners of the vehicle, including the current one.
 * @property-read SchemaTypeList<QuantitativeValue>                 $fuelEfficiency              The distance traveled per unit of fuel used; most commonly miles per gallon (mpg) or kilometers per liter (km/L).
 * @property-read SchemaTypeList<Number|QuantitativeValue>          $numberOfAxles               The number of axles.
 * @property-read SchemaTypeList<Text>                              $vehicleInteriorType         The type or material of the interior of the vehicle (e.g. synthetic fabric, leather, wood, etc.). While most interior types are characterized by the material used, an interior type can also be based on vehicle usage or target audience.
 * @property-read SchemaTypeList<Text>                              $knownVehicleDamages         A textual description of known damages, both repaired and unrepaired.
 * @property-read SchemaTypeList<Number|Text>                       $numberOfAirbags             The number or type of airbags in the vehicle.
 * @property-read SchemaTypeList<QuantitativeValue|Number>          $vehicleSeatingCapacity      The number of passengers that can be seated in the vehicle, both in terms of the physical space available, and in terms of limitations set by law.
 * @property-read SchemaTypeList<URL|QualitativeValue|Text>         $vehicleTransmission         The type of component used for transmitting the power from a rotating power source to the wheels or other relevant component(s) ("gearbox" for cars).
 * @property-read SchemaTypeList<Date>                              $dateVehicleFirstRegistered  The date of the first registration of the vehicle with the respective public authorities.
 * @property-read SchemaTypeList<Date>                              $purchaseDate                The date the item e.g. vehicle was purchased by the current owner.
 * @property-read SchemaTypeList<DriveWheelConfigurationValue|Text> $driveWheelConfiguration     The drive wheel configuration, i.e. which roadwheels will receive torque from the vehicle's engine via the drivetrain.
 */
interface Vehicle extends Product
{
}
