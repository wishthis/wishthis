<?php

/**
 * wishes.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{User, Wish, EmbedCache};

$api      = true;
$response = array(
    'success' => false,
);

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['wish_id'], $_GET['wishlist_user'])) {
            $columns = $database
            ->query('SELECT *
                       FROM `wishes`
                      WHERE `id` = ' . $_GET['wish_id'] . ';')
            ->fetch();

            $wish = new wish($columns);

            $response = array(
                'info' => $wish,
                'html' => $wish->getCard($_GET['wishlist_user'], true)
            );
        } elseif (isset($_GET['wish_url'])) {
            $cache  = new EmbedCache($_GET['wish_url']);
            $info   = $cache->get(true);
            $exists = $cache->exists() ? 'true' : 'false';

            $response = array(
                'info' => $info
            );
        }
        break;

    case 'POST':
        if (isset($_POST['wishlist_id'], $_POST['wish_url'])) {
            /**
             * Insert New Wish
             */
            $database->query('INSERT INTO `wishes`
                             (
                                `wishlist`,
                                `title`,
                                `description`,
                                `url`
                             ) VALUES ('
                                . $_POST['wishlist_id'] . ',
                                "' . $_POST['wish_title'] . '",
                                "' . $_POST['wish_description'] . '",
                                "' . $_POST['wish_url'] . '"
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

        if (isset($_PUT['wish_id'], $_PUT['wish_status'])) {
            /**
             * Update Wish Status
             */
            $database->query('UPDATE `wishes`
                                 SET `status` = "' . $_PUT['wish_status'] . '"
                               WHERE `id` = ' . $_PUT['wish_id'] . '
            ;');

            $response['success'] = true;
        } elseif (isset($_PUT['wish_url_current'], $_PUT['wish_url_proposed'])) {
            /**
             * Update Wish URL
             */
            $database->query('UPDATE `wishes`
                                 SET `url` = "' . $_PUT['wish_url_proposed'] . '"
                               WHERE `url` = "' . $_PUT['wish_url_current'] . '"
            ;');

            $response['success'] = true;
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);

        if (isset($_DELETE['wish_id'])) {
            $database->query('DELETE FROM `wishes`
                                    WHERE `id` = ' . $_DELETE['wish_id'] . '
            ;');

            $response['success'] = true;
        }
        break;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
