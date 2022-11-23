<?php

/**
 * wishlists.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

global $page, $database;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_POST['wishlist-name'], $_SESSION['user']->id)) {
            /**
             * Create
             */
            $user_id   = Sanitiser::getNumber($_SESSION['user']->id);
            $wish_name = Sanitiser::getTitle($_POST['wishlist-name']);

            $database->query(
                'INSERT INTO `wishlists` (
                    `user`,
                    `name`,
                    `hash`
                ) VALUES (
                     ' . $user_id . ',
                    "' . $wish_name . '",
                    "' . sha1(time() . $user_id . $wish_name) . '"
                );'
            );

            $response['data'] = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        } elseif (isset($_POST['wishlist-id'])) {
            /**
             * Request more wishes
             */
            $wishlistID = Sanitiser::getNumber($_POST['wishlist-id']);

            /** Get last notification time */
            $wishlistQuery = $database
            ->query(
                'SELECT *
                   FROM `wishlists`
                  WHERE `id` = ' . $wishlistID . '
                    AND (`notification_sent` < (CURRENT_TIMESTAMP - INTERVAL 1 DAY) OR `notification_sent` IS NULL);'
            );

            $wishlist = $wishlistQuery->fetch();

            /** Set notification time */
            if (false !== $wishlist) {
                $href = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . Page::PAGE_WISHLISTS . '&id=' . $wishlist['id'];

                /** Send email */
                $user  = User::getFromID($wishlist['user']);
                $email = new Email($user->email, __('Wish request', null, $user), 'default', 'wishlist-request-wishes');
                $email->setPlaceholder('TEXT_HELLO', __('Hello,', null, $user));
                $email->setPlaceholder(
                    'TEXT_WISHLIST_REQUEST_WISHES',
                    sprintf(
                        /** TRANSLATORS: %s: Wishlist name */
                        __('somebody has requested that you add more wishes to your wishlist %s.', null, $user),
                        '<a href="' . $href . '">' . $wishlist['name'] . '</a>'
                    )
                );
                $email->setPlaceholder('TEXT_WISH_ADD', __('Add wish', null, $user));
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
        if (isset($_GET['wishlist_id'], $_GET['priority'])) {
            /**
             * Get wishlist cards with priority
             */
            $wishlist = new Wishlist($_GET['wishlist_id']);
            $options  = array(
                'style' => $_GET['style'],
            );
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

            $options['WHERE'] = '(' . implode(') AND (', $where) . ')';

            $response['results'] = $wishlist->getCards($options);
        } elseif (isset($_GET['wishlist_id'])) {
            /**
             * Get wishlist by id
             */
            $wishlist = new Wishlist($_GET['wishlist_id']);

            $response['results'] = $wishlist;
        } elseif (isset($_GET['userid']) || isset($_SESSION['user']->id)) {
            /**
             * Get user wishlists
             */
            $user = isset($_GET['userid']) ? User::getFromID($_GET['userid']) : $_SESSION['user'];

            $wishlists = array();
            $options   = array(
                'style' => $_GET['style'],
            );

            foreach ($user->getWishlists() as $wishlist_result) {
                $wishlist = new Wishlist($wishlist_result['id']);

                $wishlists[$wishlist->id] = array(
                    'name'  => $wishlist->name,
                    'value' => $wishlist->id,
                    'text'  => $wishlist->name,
                );
            }

            $response['results'] = $wishlists;
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);

        $database
        ->query(
            'UPDATE `wishlists`
                SET `name` = "' . Sanitiser::getTitle($_PUT['wishlist_title']) . '"
              WHERE `id`   =  ' . Sanitiser::getNumber($_PUT['wishlist_id']) . ';'
        );

        $response['success'] = true;
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);

        $database->query(
            'DELETE FROM `wishlists`
                   WHERE `id` = ' . Sanitiser::getNumber($_DELETE['wishlistID']) . ';'
        );

        $response['success'] = true;
        break;
}
