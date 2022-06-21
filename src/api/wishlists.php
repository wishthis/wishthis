<?php

/**
 * wishlists.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$api      = true;
$response = array();

ob_start();

require '../../index.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_POST['wishlist-name'], $_SESSION['user']['id'])) {
            /**
             * Create
             */
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
        } elseif (isset($_POST['wishlist-id'])) {
            /**
             * Request more wishes
             */
            $wishlistID = $_POST['wishlist-id'];

            /** Get last notification time */
            $wishlistQuery = $database
            ->query(
                'SELECT *
                   FROM `wishlists`
                  WHERE `id` = ' . $wishlistID . '
                    AND (`notification_sent` < UNIX_TIMESTAMP(CURRENT_TIMESTAMP - INTERVAL 1 DAY) OR `notification_sent` IS NULL);'
            );

            $wishlist = $wishlistQuery->fetch();

            /** Set notification time */
            if (false !== $wishlist) {
                $href = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . Page::PAGE_WISHLISTS . '&id=' . $wishlist['id'];

                /** Send email */
                $user  = new User($wishlist['user']);
                $email = new Email($user->email, __('Wish request'), 'default', 'wishlist-request-wishes');
                $email->setPlaceholder('TEXT_HELLO', __('Hello,'));
                $email->setPlaceholder(
                    'TEXT_WISHLIST_REQUEST_WISHES',
                    sprintf(
                        /** TRANSLATORS: %s: Wishlist name */
                        __('somebody has requested that you add more wishes to your wishlist %s.'),
                        '<a href="' . $href . '">' . $wishlist['name'] . '</a>'
                    )
                );
                $email->setPlaceholder('TEXT_WISH_ADD', __('Add wish'));
                $email->setPlaceholder('LINK_WISH_ADD', $href . '&wish_add=true');

                $success = $email->send();

                /** Save date to database */
                if (true === $success) {
                    $database
                    ->query(
                        'UPDATE `wishlists`
                            SET `notification_sent` = CURRENT_TIMESTAMP
                          WHERE `id` = ' . $wishlist['id'] . ';'
                    );
                }
            }

            $response['success']        = true;
            $response['email_was_sent'] = false !== $wishlist;
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
                'WHERE' => '(' . implode(') AND (', $where) . ')',
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
