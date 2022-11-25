<?php

/**
 * wishthis - Make a wish
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

define('VERSION', '0.7.2');
define('ROOT', __DIR__);
define('DEFAULT_LOCALE', 'en_GB');
define('COOKIE_PERSISTENT', 'wishthis_persistent');

/**
 * Include
 */
require 'vendor/autoload.php';

$include = new \Grandel\IncludeDirectory(__DIR__ . '/src/functions');

spl_autoload_register(
    function (string $fullClass) {
        /** Only include classes from this namespace */
        if (__NAMESPACE__ === substr($fullClass, 0, strlen(__NAMESPACE__))) {
            $fullClass = substr($fullClass, strlen(__NAMESPACE__));
        } else {
            return false;
        }

        $parts = explode('\\', $fullClass);
        $class = implode('/', $parts);

        $filepath = ROOT . '/src/classes/' . strtolower($class) . '.php';

        require $filepath;
    }
);

/**
 * Config
 */
$configPath = __DIR__ . '/' . 'src/config/config.php';

if (file_exists($configPath)) {
    require $configPath;
}

/**
 * Session
 */
session_start(
    array(
        'name' => 'wishthis',
    )
);

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new User();
}

/**
 * Database
 */
$database = false;
$options  = false;

if (
       defined('DATABASE_HOST')
    && defined('DATABASE_NAME')
    && defined('DATABASE_USER')
    && defined('DATABASE_PASSWORD')
) {
    $database = new Database(
        DATABASE_HOST,
        DATABASE_NAME,
        DATABASE_USER,
        DATABASE_PASSWORD
    );

    /**
     * Options
     */
    $options = new Options($database);
}

/**
 * Persistent (stay logged in)
 */
if (isset($_COOKIE[COOKIE_PERSISTENT]) && $database) {
    $table_sessions_exists = $database->tableExists('sessions');

    if ($table_sessions_exists) {
        $sessions = $database
        ->query(
            'SELECT *
               FROM `sessions`
              WHERE `session` = "' . $_COOKIE[COOKIE_PERSISTENT] . '";'
        )
        ->fetchAll();

        if (false !== $sessions) {
            $_SESSION['user'] = new User();

            foreach ($sessions as $session) {
                /** Column sessions.expires was added in v0.7.1. */
                $expires = strtotime($session['expires'] ?? date('Y-m-d H:i:s', time() + 1));

                if (time() < $expires) {
                    $_SESSION['user'] = User::getFromID($session['user']);

                    break;
                }
            }
        }
    }
}

/**
 * Language
 */
\Locale::setDefault(DEFAULT_LOCALE);

/** Determine Locale */
$locales = array_filter(
    array_map(
        function ($value) {
            $extension = pathinfo($value, PATHINFO_EXTENSION);
            $filename  = pathinfo($value, PATHINFO_FILENAME);

            if ('po' === $extension) {
                return pathinfo($value, PATHINFO_FILENAME);
            }
        },
        scandir(ROOT . '/translations')
    )
);

$locale = isset($_REQUEST['locale']) ? $_REQUEST['locale'] : \Locale::lookup($locales, $_SESSION['user']->getLocale(), false, 'en_GB');

/**
 * Wish
 */
Wish::initialize();

/**
 * API
 */
if (isset($api)) {
    return;
}

/**
 * Pretty URLs
 */
$url = new URL($_SERVER['REQUEST_URI']);

/**
 * Install
 */
if (!$options || !$options->getOption('isInstalled')) {
    $page = 'install';
}

/**
 * Database Update
 */
if ($options && $options->getOption('isInstalled')) {
    if (-1 === version_compare($options->version, VERSION)) {
        $options->setOption('updateAvailable', true);
    }
}

/**
 * Page
 */
if (!isset($page)) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
}
$pagePath = 'src/pages/' . $page . '.php';

if (file_exists($pagePath)) {
    require $pagePath;
} else {
    http_response_code(404);
    ?>
    <h1>Not found</h1>
    <p>The requested URL was not found on this server.</p>
    <?php
    echo $pagePath;
    die();
}
