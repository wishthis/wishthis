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
require 'includes/functions/auto-include.php';

autoInclude(__DIR__ . '/includes/classes');
autoInclude(__DIR__ . '/includes/functions');

/**
 * Config
 */
$configPath = 'includes/config/config.php';

if (file_exists($configPath)) {
    require $configPath;
}

/**
 * Database
 */
$database = false;

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
}

/**
 * Install
 */
if ($database) {
    try {
        $database->getOption('isInstalled');
    } catch (\Throwable $th) {
        $page = 'install';
    }
}

/**
 * Session
 */
session_start();

/**
 * Page
 */
if (!isset($page)) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
}
$pagePath = 'includes/pages/' . $page . '.php';

require $pagePath;
