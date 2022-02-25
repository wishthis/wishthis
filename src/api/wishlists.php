<?php

/**
 * wishlists.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{User, Wishlist};

$api      = true;
$response = array(
    'success' => false,
);

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

            $response['success'] = true;
            $response['data']    = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        }
        break;

    case 'GET':
        if (isset($_GET['userid']) || isset($_SESSION['user']['id'])) {
            $user = isset($_GET['userid']) ? new User($_GET['userid']) : new User();

            $wishlists = $user->getWishlists();
            $wishlists = array_map(function ($dataWishlist) {
                $data = $dataWishlist;
                // $newFormat['name'] = $wishlist['name'];
                $data['value'] = $dataWishlist['id'];
                $data['text'] = $dataWishlist['name'];

                $wishlist = new Wishlist(intval($dataWishlist['id']));
                $data['cards'] = $wishlist->getCards();

                return $data;
            }, $wishlists);

            $response['results'] = $wishlists;
            $response['success'] = true;
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);

        $database->query('DELETE FROM `wishlists`
            WHERE `id` = ' . $_DELETE['wishlistID'] . '
        ;');

        $response['success'] = true;
        break;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
die();
