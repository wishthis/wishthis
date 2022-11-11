<?php

/**
 * API
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('API'));
$api  = new API();
$api->do();
