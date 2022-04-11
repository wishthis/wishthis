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
        $wishlists = $database
        ->query('SELECT *
                   FROM `wishlists_saved` `ws`
                   JOIN `wishlists`       `w`  ON `w`.`id` = `ws`.`wishlist`
                  WHERE `ws`.`user` = ' . $user->id . ';')
        ->fetchAll();

        $response['data'] = $wishlists;
        break;

    case 'POST':
        if (isset($_POST['wishlist'])) {
            $wishlist = $database
            ->query('SELECT *
                       FROM `wishlists_saved`
                      WHERE `wishlist` = ' . $_POST['wishlist'] . '
            ;')
            ->fetch();

            if ($wishlist) {
                /** Delete */
                $database
                ->query('DELETE FROM `wishlists_saved`
                               WHERE `wishlist` = ' . $_POST['wishlist'] . '
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
                    ' . $_POST['wishlist'] . '
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
