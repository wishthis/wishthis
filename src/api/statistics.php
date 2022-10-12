<?php

/**
 * Various statistics
 *
 * @category API
 */

namespace wishthis;

$api      = true;
$response = array();

ob_start();

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['table'])) {
            if ('all' === $_GET['table']) {
                $tables = array(
                    'wishes',
                    'wishlists',
                    'users',
                );

                $response['data'] = array();

                foreach ($tables as $table) {
                    $count = new Cache\Query(
                        'SELECT COUNT(`id`) AS "count"
                           FROM `' . $table . '`;',
                        Duration::DAY
                    );

                    $response['data'][$table] = $count->get();
                }
            } else {
                $table = Sanitiser::getTable($_GET['table']);

                $count = $database
                ->query(
                    'SELECT COUNT(`id`) AS "count"
                       FROM `' . $table . '`;'
                )
                ->fetch();

                $response['data'] = $count;
            }
        }
        break;
}

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
