<?php

/**
 * wishlists.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\User;

$api = true;
$response = array(
    'success' => false,
);

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['userid']) || isset($_SESSION['user']['id'])) {
            $user = isset($_GET['userid']) ? new User($_GET['userid']) : new User();
            $wishlists = $user->getWishlists();
            $wishlists = array_map(function ($wishlist) {
                return array(
                    'name'  => $wishlist['name'],
                    'value' => $wishlist['id'],
                    'text'  => $wishlist['name'],
                );
            }, $wishlists);

            $response['results'] = $wishlists;
            $response['success'] = true;
        }
        break;
}

echo json_encode($response);
header('Content-type: application/json; charset=utf-8');
die();
