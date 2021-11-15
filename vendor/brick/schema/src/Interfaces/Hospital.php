<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

/**
 * http://schema.org/Hospital
 */
interface Hospital extends MedicalOrganization, CivicStructure, EmergencyService
{
}
