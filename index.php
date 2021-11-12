<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

 /**
 * Include
 */
require 'includes/functions/auto-include.php';

autoInclude(__DIR__ . '/includes/classes');
autoInclude(__DIR__ . '/includes/functions');

/**
 * Install
 */
$configPath = 'includes/config/config.php';

if (!file_exists($configPath)) {
    $page = 'install';
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
}
