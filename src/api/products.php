<?php

/**
 * products.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\User;

$api      = true;
$response = array(
    'success' => false,
);

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);

        if (isset($_PUT['productID'], $_PUT['productStatus'])) {
            $database->query('UPDATE `products`
                                 SET `status` = "' . $_PUT['productStatus'] . '"
                               WHERE `id` = ' . $_PUT['productID'] . '
            ;');

            $response['success'] = true;
        } elseif (isset($_PUT['wishlist_id'], $_PUT['url'])) {
            $database->query('INSERT INTO `products`
                             (
                             `wishlist`,
                             `url`
                             ) VALUES ('
                             . $_PUT['wishlist_id'] . ',
                             "' . $_PUT['url'] . '"
                             )
            ;');

            $response['success'] = true;
            $response['data']    = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);

        if (isset($_DELETE['productID'])) {
            $database->query('DELETE FROM `products`
                                    WHERE `id` = ' . $_DELETE['productID'] . '
            ;');

            $response['success'] = true;
        }
        break;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
