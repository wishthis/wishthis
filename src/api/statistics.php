<?php

/**
 * Various statistics
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

$api      = true;
$response = array(
    'success' => false,
);

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['table'])) {
            if ('all' === $_GET['table']) {
                $tables = array(
                    'products',
                    'wishlists',
                    'users',
                );

                $response['data'] = array();

                foreach ($tables as $table) {
                    $count = $database
                             ->query('SELECT COUNT(`id`) AS "count"
                                     FROM `' . $table . '`;')
                             ->fetch();

                    $response['data'][$table] = $count;
                }

                $response['success'] = true;
            } else {
                $count = $database
                ->query('SELECT COUNT(`id`) AS "count"
                           FROM `' . $_GET['table'] . '`;')
                ->fetch();

                $response['data']    = $count;
                $response['success'] = true;
            }
        }
        break;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
