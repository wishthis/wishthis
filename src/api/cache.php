<?php

/**
 * Generate cache for product
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\EmbedCache;

$api      = true;
$response = array(
    'success' => false,
);

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $cache = new EmbedCache($_GET['url']);
        $info  = $cache->get(true);

        if ($info->url) {
            $database->query('UPDATE `products`
                                 SET `url` = "' . $info->url . '"
                               WHERE `id` = ' . $_GET['product_id'] . '
            ;');
        }

        $response['success'] = true;
        $response['data']    = $info;
        break;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
