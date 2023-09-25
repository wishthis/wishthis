<?php

/**
 * wishlists-saved.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

global $page, $database;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

$user = User::getCurrent();

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
            ->query(
                'SELECT *
                   FROM `wishlists_saved`
                  WHERE `wishlist` = :wishlist_id',
                array(
                    'wishlist_id' => Sanitiser::getNumber($_POST['wishlist'])
                )
            )
            ->fetch();

            if ($wishlist) {
                /** Delete */
                $database
                ->query(
                    'DELETE FROM `wishlists_saved`
                           WHERE `wishlist` = :wishlist_id',
                    array(
                        'wishlist_id' => Sanitiser::getNumber($_POST['wishlist'])
                    )
                );

                $response['action'] = 'deleted';
            } else {
                /** Create */
                $database
                ->query(
                    'INSERT INTO `wishlists_saved` (
                        `user`,
                        `wishlist`
                    ) VALUES (
                        :user_id,
                        :wishlist_id
                    );',
                    array(
                        'user_id'     => $user->getId(),
                        'wishlist_id' => Sanitiser::getNumber($_POST['wishlist']),
                    )
                );

                $response['action'] = 'created';
            }
        }
        break;
}
