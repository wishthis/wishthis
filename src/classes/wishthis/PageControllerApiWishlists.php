<?php

namespace wishthis;

class PageControllerApiWishlists extends PageController
{
    protected string $id = 'api-wishlists';

    private int $wishlistId;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = 'API';

        $this->wishlistId = $parameters['id'];

        parent::__construct();
    }

    public function get(): void
    {
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

    public function delete(): void
    {
        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        $wishlistId = $this->wishlistId;

        $database->query(
            'DELETE FROM `wishlists`
                   WHERE `wishlists`.`id`   = :wishlist_id
                     AND `wishlists`.`user` = :user_id;',
            [
                'wishlist_id' => $this->wishlistId,
                'user_id'     => $userId,
            ]
        );

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
