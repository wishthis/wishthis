<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

define('VERSION', '0.3.0');

/**
 * Include
 */
require 'vendor/autoload.php';

$include = new Grandel\IncludeDirectory(__DIR__ . '/includes/classes');
$include = new Grandel\IncludeDirectory(__DIR__ . '/includes/functions');

/**
 * Config
 */
$configPath = __DIR__ . '/' . 'includes/config/config.php';

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
session_start();

/**
 * API
 */
if (isset($api)) {
    return;
}

/**
 * Install
 */
if (!$options) {
    $page = 'install';
}

/**
 * User
 */
if ($options) {
    $user = new wishthis\User();
}

/**
 * Update
 */
use Github\Client;

if ($options) {
    $client  = new Client();
    $release = $client->api('repo')->releases()->latest('grandeljay', 'wishthis');
    $tag     = $release['tag_name'];
    $version = str_replace('v', '', $tag);

    if (-1 === version_compare($options->version, $version)) {
        $options->updateAvailable = true;
    }
}

/**
 * Wishlist
 */
if (!isset($_GET['page']) && isset($_GET['wishlist'])) {
    $page = 'wishlist';
}

/**
 * Page
 */
if (!isset($page)) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
}
$pagePath = 'includes/pages/' . $page . '.php';

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
