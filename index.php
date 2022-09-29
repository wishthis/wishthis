<?php

/**
 * wishthis - Make a wish
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

define('VERSION', '0.7.0');
define('ROOT', __DIR__);
define('DEFAULT_LOCALE', 'en_GB');

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
$cookie_domain = $_SERVER['HTTP_HOST'];

if (defined('CHANNELS') && is_iterable(CHANNELS) && defined('ENV_IS_DEV') && ! ENV_IS_DEV) {
    foreach (CHANNELS as $channel) {
        if ('stable' === $channel['branch']) {
            $cookie_domain = $channel['host'];

            break;
        }
    }
}

$sessionLifetime = 2592000 * 12; // 12 Months

session_start(
    array(
        'name'            => 'wishthis',
        'cookie_lifetime' => $sessionLifetime,
        'cookie_path'     => '/',
        'cookie_domain'   => '.' . $cookie_domain,
    )
);

/** Backwards compatibility */
if (!isset($_SESSION['user']) || is_array($_SESSION['user'])) {
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
