<?php

/**
 * Configuration
 *
 * Usually, the wishthis installer will create the config.php for you.
 */

namespace wishthis;

/**
 * Database
 *
 * Used to connect to your MySQL database.
 */
\define('DATABASE_HOST', 'localhost');
\define('DATABASE_NAME', 'wishthis');
\define('DATABASE_USER', 'root');
\define('DATABASE_PASSWORD', '');

/**
 * Development
 *
 * Keep this disabled for production sites.
 */
\define('ENV_IS_DEV', false);

/**
 * Channels
 *
 * It's safe to delete this if you are self-hosting. Alternatively you can
 * replace these branches and domains with your own.
 */
\define(
    'CHANNELS',
    [
        [
            'branch' => 'stable',
            'host'   => 'wishthis.online',
            'label'  => __('Stable'),
        ],
        [
            'branch' => 'release-candidate',
            'host'   => 'rc.wishthis.online',
            'label'  => __('Release candidate'),
        ],
    ]
);

/**
 * plausible
 *
 * Whether to make calls to plausible.io.
 */
\define('PLAUSIBLE', false);

/**
 * Image Download from URL
 *
 * Allows fetching external images and storing them locally.
 */
\define('UPLOAD_ENABLED', true);
\define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);
\define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

/**
 * Miscellaneous
 */
\define('DISABLE_USER_REGISTRATION', false);
