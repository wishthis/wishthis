<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

define('VERSION', '0.6.0');
define('ROOT', __DIR__);
define('DEFAULT_LOCALE', 'en_GB');

/**
 * Include
 */
require 'vendor/autoload.php';

$include = new Grandel\IncludeDirectory(__DIR__ . '/src/classes');
$include = new Grandel\IncludeDirectory(__DIR__ . '/src/functions');

/**
 * Config
 */
$configPath = __DIR__ . '/' . 'src/config/config.php';

if (file_exists($configPath)) {
    require $configPath;
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
    $database = new wishthis\Database(
        DATABASE_HOST,
        DATABASE_NAME,
        DATABASE_USER,
        DATABASE_PASSWORD
    );

    /**
     * Options
     */
    $options = new wishthis\Options($database);
}

/**
 * Session
 */
$sessionLifetime = 2592000; // 1 Month

session_set_cookie_params($sessionLifetime, '/');
session_start();

/** Refresh lifetime */
$session = session_get_cookie_params();

setcookie(
    session_name(),
    session_id(),
    time() + $sessionLifetime,
    $session['path'],
    $session['domain'],
    $session['secure'],
    $session['httponly']
);

/** Forwards compatibility */
if (isset($_SESSION['user']) && $_SESSION['user'] instanceof wishthis\User) {
    $_SESSION['user'] = array(
        'id'    => $_SESSION['user']->id,
        'email' => $_SESSION['user']->email,
    );
}

/**
 * User
 */
if ($options) {
    $user = new wishthis\User();
}

/**
 * Language
 */
\Locale::setDefault(DEFAULT_LOCALE);

/** Determine Locale */
$locales = array_filter(
    array_map(
        function ($value) {
            if ('po' === pathinfo($value, PATHINFO_EXTENSION)) {
                return pathinfo($value, PATHINFO_FILENAME);
            }
        },
        scandir(ROOT . '/translations')
    )
);
$locale  = \Locale::lookup($locales, $user->locale, false, DEFAULT_LOCALE);

/** Load Translation */
$translationFilepath = ROOT . '/translations/' . $locale . '.po';
$translations        = null;

if (file_exists($translationFilepath)) {
    $loader       = new \Gettext\Loader\PoLoader();
    $translations = $loader->loadFile($translationFilepath);
}

/**
 * Wish
 */
wishthis\Wish::initialize();

/**
 * API
 */
if (isset($api)) {
    return;
}

/**
 * Pretty URLs
 */
$url = new \wishthis\URL($_SERVER['REQUEST_URI']);

if ($url->isPretty()) {
    $_SESSION['_GET'] = query_to_key_value_pair($url->getPermalink());
}

if ($_SERVER['QUERY_STRING']) {
    $_SESSION['_GET'] = $_GET;
}

/**
 * Install
 */
if (!$options || !$options->getOption('isInstalled')) {
    $page = 'install';
}

/**
 * Database Update
 */
if ($options && $options->getOption('isInstalled') && !(defined('ENV_IS_DEV') && ENV_IS_DEV)) {
    if (-1 === version_compare($options->version, VERSION)) {
        $options->setOption('updateAvailable', true);
    }
}

/**
 * Page
 */
if (!isset($page)) {
    $page = isset($_SESSION['_GET']['page']) ? $_SESSION['_GET']['page'] : 'home';
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
