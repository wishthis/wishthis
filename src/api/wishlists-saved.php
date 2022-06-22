<?php

/**
 * wishlists-saved.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

$api      = true;
$response = array();

ob_start();

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        /**
         * Get
         */
        $response['data'] = $user->getSavedWishlists();
        break;

    case 'POST':
        if (isset($_POST['wishlist'])) {
            $wishlist = $database
            ->query('SELECT *
                       FROM `wishlists_saved`
                      WHERE `wishlist` = ' . Sanitiser::getNumber($_POST['wishlist']) . '
            ;')
            ->fetch();

            if ($wishlist) {
                /** Delete */
                $database
                ->query('DELETE FROM `wishlists_saved`
                               WHERE `wishlist` = ' . Sanitiser::getNumber($_POST['wishlist']) . '
                ;');

                $response['action'] = 'deleted';
            } else {
                /** Create */
                $database
                ->query('INSERT INTO `wishlists_saved` (
                    `user`,
                    `wishlist`
                ) VALUES (
                    ' . $user->id . ',
                    ' . Sanitiser::getNumber($_POST['wishlist']) . '
                )
                ;');

                $response['action'] = 'created';
            }
        }
        break;
}

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
