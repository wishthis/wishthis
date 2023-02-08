<?php

/**
 * Wishlists
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

global $page, $database;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_POST['wishlist-name'], $_SESSION['user']->id)) {
            /**
             * Create
             */
            $user_id       = Sanitiser::getNumber($_SESSION['user']->id);
            $wishlist_name = Sanitiser::getTitle($_POST['wishlist-name']);
            $wishlist_hash = sha1(time() . $user_id . $wishlist_name);

            $database->query(
                'INSERT INTO `wishlists` (
                    `user`,
                    `name`,
                    `hash`
                ) VALUES (
                    :user_id,
                    :wishlist_name,
                    :wishlist_hash
                );',
                array(
                    'user_id'       => $user_id,
                    'wishlist_name' => $wishlist_name,
                    'wishlist_hash' => $wishlist_hash,
                )
            );

            $response['data'] = array(
                'lastInsertId' => $database->lastInsertId(),
            );
        } elseif (isset($_POST['wishlist-id'])) {
            /**
             * Request more wishes
             */
            $wishlist_id = Sanitiser::getNumber($_POST['wishlist-id']);

            /** Get last notification time */
            $wishlistQuery = $database
            ->query(
                'SELECT *
                   FROM `wishlists`
                  WHERE `id` = :wishlist_id
                    AND (`notification_sent` < (CURRENT_TIMESTAMP - INTERVAL 1 DAY) OR `notification_sent` IS NULL);',
                array(
                    'wishlist_id' => $wishlist_id,
                )
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
                          WHERE `id` = :wishlist_id;',
                        array(
                            'wishlist_id' => $wishlist['id'],
                        )
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
                'style'        => $_GET['style'],
                'placeholders' => array(),
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

            if ($wishlist->exists) {
                /** Determine if user is allowed to access wishlist */
                if ($_SESSION['user']->isLoggedIn() && $_SESSION['user']->id === $wishlist->user) {
                    $response['results'] = $wishlist;
                } else {
                    http_response_code(403);
                }
            } else {
                http_response_code(404);
            }
        } elseif (isset($_GET['wishlist_hash'])) {
            /**
             * Get wishlist by hash
             */
            $wishlist = new Wishlist($_GET['wishlist_hash']);

            if ($wishlist->exists) {
                $response['results'] = $wishlist;
            } else {
                http_response_code(404);
            }
        } elseif (isset($_GET['userid']) || isset($_SESSION['user']->id)) {
            /**
             * Get user wishlists
             */
            $user = $_SESSION['user'];

            if (!$user->isLoggedIn()) {
                $this->response(403);
            }

            $wishlists       = array();
            $wishlists_items = array();

            foreach ($user->getWishlists() as $wishlist_result) {
                $wishlist = new Wishlist($wishlist_result['id']);

                $wishlists[$wishlist->id]       = $wishlist;
                $wishlists_items[$wishlist->id] = array(
                    'name'  => $wishlist->name,
                    'value' => $wishlist->id,
                    'text'  => $wishlist->name,
                );
            }

            $response['wishlists']       = $wishlists;
            $response['wishlists_items'] = $wishlists_items;
        }
        break;

    case 'PUT':
        $_PUT = $this->input;

        $database
        ->query(
            'UPDATE `wishlists`
                SET `name` = :wishlist_name
              WHERE `id`   = :wishlist_id',
            array(
                'wishlist_name' => Sanitiser::getTitle($_PUT['wishlist_title']),
                'wishlist_id'   => Sanitiser::getNumber($_PUT['wishlist_id']),
            )
        );

        $response['success'] = true;
        break;

    case 'DELETE':
        $_DELETE = $this->input;

        $database->query(
            'DELETE FROM `wishlists`
                   WHERE `id` = :wishlist_id;',
            array(
                'wishlist_id' => Sanitiser::getNumber($_DELETE['wishlist_id']),
            )
        );

        $response['success'] = true;
        break;
}
