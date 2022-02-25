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
    case 'POST':
        if (isset($_POST['wishlist_id'], $_POST['product_url'])) {
            /**
             * Insert New Product
             */
            $database->query('INSERT INTO `products`
                             (
                                `wishlist`,
                                `url`
                             ) VALUES ('
                                . $_POST['wishlist_id'] . ',
                                "' . $_POST['product_url'] . '"
                             )
            ;');

            $response['success'] = true;
            $response['data']    = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);

        if (isset($_PUT['productID'], $_PUT['productStatus'])) {
            /**
             * Update Product Status
             */
            $database->query('UPDATE `products`
                                 SET `status` = "' . $_PUT['productStatus'] . '"
                               WHERE `id` = ' . $_PUT['productID'] . '
            ;');

            $response['success'] = true;
        } elseif (isset($_PUT['product_url_current'], $_PUT['product_url_proposed'])) {
            /**
             * Update Product URL
             */
            $database->query('UPDATE `products`
                                 SET `url` = "' . $_PUT['product_url_proposed'] . '"
                               WHERE `url` = "' . $_PUT['product_url_current'] . '"
            ;');

            $response['success'] = true;
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
