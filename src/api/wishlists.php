<?php

/**
 * wishlists.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{User, Wishlist};

$api      = true;
$response = array();

ob_start();

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        /**
         * Create
         */
        if (isset($_POST['wishlist-name'], $_SESSION['user']['id'])) {
            $database->query('INSERT INTO `wishlists`
                (
                    `user`,
                    `name`,
                    `hash`
                ) VALUES (
                    ' . $_SESSION['user']['id'] . ',
                    "' . $_POST['wishlist-name'] . '",
                    "' . sha1(time() . $_SESSION['user']['id'] . $_POST['wishlist-name']) . '"
                )
            ;');

            $response['data'] = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        }
        break;

    case 'GET':
        if (isset($_GET['wishlist'], $_GET['priority'])) {
            /**
             * Get wishlist cards
             */
            $wishlist = new Wishlist($_GET['wishlist']);
            $options  = array();
            $where    = array(
                'wishlist' => '`wishlist` = ' . $wishlist->id,
                'priority' => '`priority` = ' . $_GET['priority'],
            );

            if (-1 == $_GET['priority']) {
                unset($where['priority']);
            }

            if (empty($_GET['priority'])) {
                $where['priority'] = '`priority` IS NULL';
            }

            $options = array(
                'WHERE' => implode(' AND ', $where),
            );

            $response['results'] = $wishlist->getCards($options);
        } elseif (isset($_GET['userid']) || isset($_SESSION['user']['id'])) {
            /**
             * Get user wishlists
             */
            $user = isset($_GET['userid']) ? new User($_GET['userid']) : new User();

            $wishlists = $user->getWishlists();
            $wishlists = array_map(
                function ($dataWishlist) {
                    /**
                     * Format wishlists to fit FUI dropdown
                     */
                    $data          = $dataWishlist;
                    $data['value'] = $dataWishlist['id'];
                    $data['text']  = $dataWishlist['name'];

                    $wishlist      = new Wishlist($dataWishlist['id']);
                    $data['cards'] = $wishlist->getCards();

                    return $data;
                },
                $wishlists
            );

            $response['results'] = $wishlists;
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);

        $database
        ->query('UPDATE `wishlists`
                    SET `name` = "' . $_PUT['wishlist_title'] . '"
                  WHERE `id`   = ' . $_PUT['wishlist_id'] . '
        ;');

        $response['success'] = true;
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);

        $database->query('DELETE FROM `wishlists`
            WHERE `id` = ' . $_DELETE['wishlistID'] . '
        ;');

        $response['success'] = true;
        break;
}

$response['warning'] = ob_get_clean();

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
