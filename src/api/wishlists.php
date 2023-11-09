<?php

/**
 * Wishlists
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

global $page, $database;

$user = User::getCurrent();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_POST['wishlist-name'])) {
            /**
             * Create
             */
            $user_id       = Sanitiser::getNumber($user->getId());
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
                $email = new Email($user->getEmail(), __('Wish request', null, $user), 'default', 'wishlist-request-wishes');
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
        $getWishlistCardsFromPriority = isset($_GET['wishlist_id'], $_GET['priority']);
        $getWishlistFromHash          = isset($_GET['wishlist_hash']);
        $getOwnWishlists              = $user->isLoggedIn();

        if ($getWishlistCardsFromPriority) {
            if (!$user->isLoggedIn()) {
                http_response_code(403);
                return;
            }

            $wishlist           = Wishlist::getFromId($_GET['wishlist_id']);
            $userWishlistsQuery = $database
            ->query(
                'SELECT `id`
                   FROM `wishlists`
                  WHERE `user` = :wishlist_user_id',
                array(
                    'wishlist_user_id' => $user->getId(),
                )
            );

            if (false === $wishlist || false === $userWishlistsQuery) {
                http_response_code(404);
                return;
            }

            $userWishlistsResults = \array_map(
                function ($result) {
                    return $result['id'];
                },
                $userWishlistsQuery->fetchAll()
            );

            $userOwnsRequestedWishlist = \in_array($wishlist->getId(), $userWishlistsResults, true);

            if (!$userOwnsRequestedWishlist) {
                http_response_code(403);
                return;
            }

            $priorityAll  = -1;
            $priorityNone = 0;
            $priority     = (int) $_GET['priority'] ?? $priorityAll;

            $options = array(
                'style'        => $_GET['style'],
                'placeholders' => array(),
            );
            $where   = array(
                'wishlist' => '`wishlist` = ' . $wishlist->getId(),
                'priority' => '`priority` = ' . $priority,
            );

            if ($priorityAll === $priority) {
                unset($where['priority']);
            }

            if ($priorityNone === $priority) {
                $where['priority'] = '`priority` IS NULL OR `priority` = 0';
            }

            $options['WHERE'] = '(' . implode(') AND (', $where) . ')';

            $response['results'] = $wishlist->getCards($options);
        } elseif ($getWishlistFromHash) {
            $wishlist = Wishlist::getFromHash($_GET['wishlist_hash']);

            if ($wishlist instanceof Wishlist) {
                $response['results'] = array(
                    'id'     => $wishlist->getId(),
                    'hash'   => $wishlist->getHash(),
                    'userId' => $wishlist->getUserId(),
                );
                ;
            } else {
                http_response_code(404);
            }
        } elseif ($getOwnWishlists) {
            $wishlists      = array();
            $wishlistsItems = array();

            foreach ($user->getWishlists() as $wishlistData) {
                $wishlist     = new Wishlist($wishlistData);
                $wishlistId   = $wishlist->getId();
                $wishlistName = $wishlist->getName();

                $wishlists[]      = array(
                    'id'     => $wishlistId,
                    'hash'   => $wishlist->getHash(),
                    'userId' => $wishlist->getUserId(),
                );
                $wishlistsItems[] = array(
                    'name'  => $wishlistName,
                    'value' => $wishlistId,
                    'text'  => $wishlistName,
                );
            }

            $response['wishlists']      = $wishlists;
            $response['wishlistsItems'] = $wishlistsItems;
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
