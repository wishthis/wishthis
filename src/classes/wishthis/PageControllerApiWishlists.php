<?php

namespace wishthis;

class PageControllerApiWishlists extends PageController
{
    protected string $id = 'api-wishlists';

    private int $wishlistId;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = 'API';

        if (isset($parameters['id'])) {
            $this->wishlistId = $parameters['id'];
        }

        parent::__construct();
    }

    public function get(): void
    {
        \ob_start();

        $user = User::getCurrent();

        $wishlists      = [];
        $wishlistsItems = [];

        foreach ($user->getWishlists() as $wishlistData) {
            $wishlist     = new Wishlist($wishlistData);
            $wishlistId   = $wishlist->getId();
            $wishlistName = $wishlist->getName();

            $wishlists[]      = [
                'id'     => $wishlistId,
                'hash'   => $wishlist->getHash(),
                'userId' => $wishlist->getUserId(),
            ];
            $wishlistsItems[] = [
                'name'  => $wishlistName,
                'value' => $wishlistId,
                'text'  => $wishlistName,
            ];
        }

        $response['wishlists']      = $wishlists;
        $response['wishlistsItems'] = $wishlistsItems;

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }

    public function create(): void
    {
        global $database;

        \ob_start();

        $user   = User::getCurrent();
        $userId = $user->getId();

        $wishlistName = Sanitiser::getTitle($_POST['wishlist-name']);
        $wishlistHash = \sha1(\time() . $userId . $wishlistName);

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
            [
                'user_id'       => $userId,
                'wishlist_name' => $wishlistName,
                'wishlist_hash' => $wishlistHash,
            ]
        );

        $response['data'] = [
            'lastInsertId' => $database->lastInsertId(),
        ];

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }

    public function update(): void
    {
        global $database;

        \ob_start();
        \parse_str(\file_get_contents('php://input'), $_PUT);

        $user   = User::getCurrent();
        $userId = $user->getId();

        $wishlistId   = $this->wishlistId;
        $wishlistName = Sanitiser::getTitle($_PUT['wishlist_title']);

        $database->query(
            'UPDATE `wishlists`
                SET `wishlists`.`name` = :wishlist_name
              WHERE `wishlists`.`id`   = :wishlist_id
                AND `wishlists`.`user` = :user_id',
            [
                'wishlist_name' => $wishlistName,
                'wishlist_id'   => $wishlistId,
                'user_id'       => $userId,
            ]
        );

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }

    public function delete(): void
    {
        \ob_start();

        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        $wishlistId = $this->wishlistId;

        $database->query(
            'DELETE FROM `wishlists`
                   WHERE `wishlists`.`id`   = :wishlist_id
                     AND `wishlists`.`user` = :user_id;',
            [
                'wishlist_id' => $wishlistId,
                'user_id'     => $userId,
            ]
        );

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
