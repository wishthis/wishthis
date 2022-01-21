<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

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

$client  = new Client();
$release = $client->api('repo')->releases()->latest('grandeljay', 'wishthis');
$tag     = $release['tag_name'];
$version = str_replace('v', '', $tag);

$zip_filename = __DIR__ . '/' . $tag . '.zip';

/** Download */
file_put_contents(
    $zip_filename,
    file_get_contents('https://github.com/grandeljay/wishthis/archive/refs/tags/' . $tag . '.zip')
);

/** Decompress */
$zip = new ZipArchive();

if ($zip->open($zip_filename)) {
    $zip->extractTo(__DIR__);
    $zip->close();

    $directory_wishthis_github = __DIR__ . '/wishthis-' . $version;

    foreach (scandir($directory_wishthis_github) as $filename) {
        if (in_array($filename, array('.', '..'))) {
            continue;
        }

        $filepath = $directory_wishthis_github . '/' . $filename;

        if (is_file($filepath)) {
            echo 'Rename ' . $filepath . ' to ' . __DIR__ . '/' . $filename . '<br>';
        }

        if (is_dir($filepath)) {
            delete_directory($filepath);
        }

        rename($filepath, __DIR__ . '/' . $filename);
    }
}

/** Delete */
unlink($zip_filename);

echo '<pre>';
var_Dump($release);
echo '</pre>';
die();

$releases = json_decode(file_get_contents('https://api.github.com/repos/grandeljay/wishthis/releases'));
$version  = $releases[0]->tag_name;

die($version);

define('VERSION', '0.3.0');

if ($options) {
    if (-1 === version_compare($options->version, VERSION)) {
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
