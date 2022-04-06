<?php

/**
 * config-sample.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'wishthis');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', '');

define('ENV_IS_DEV', false);

define(
    'CHANNELS',
    array(
        'stable'            => 'wishthis.online',
        'release-candidate' => 'rc.wishthis.online',
    )
);
