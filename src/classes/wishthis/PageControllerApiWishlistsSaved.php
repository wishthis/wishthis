<?php

namespace wishthis;

class PageControllerApiWishlistsSaved extends PageController
{
    protected string $id = 'api-wishlists-saved';

    private Wishlist $wishlist;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = 'API';

        if (isset($parameters['hash'])) {
            $this->wishlist = Wishlist::getFromHash($parameters['hash']);
        }

        parent::__construct();
    }

    public function get(): void
    {
        \ob_start();

        $user           = User::getCurrent();
        $userId         = $user->getId();
        $userIsLoggedIn = $user->isLoggedIn();

        $wishlistId = $this->wishlist->getId();

        if ($userIsLoggedIn) {
            global $database;

            $result = $database->query(
                '  SELECT 1
                     FROM `wishlists_saved`
                LEFT JOIN `wishlists` ON `wishlists`.`id` = `wishlists_saved`.`wishlist`
                    WHERE `wishlists_saved`.`user`     = :user_id
                      AND `wishlists_saved`.`wishlist` = :wishlist_id;',
                [
                    'user_id'     => $userId,
                    'wishlist_id' => $wishlistId,
                ]
            )
            ->fetchColumn();

            $isSaved = false !== $result;

            $response['data'] = [
                'isSaved' => $isSaved,
            ];
        } else {
            $response['data'] = [
                'isSaved' => false,
            ];
        }

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }

    public function create(): void
    {
        \ob_start();

        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        $wishlistId = $this->wishlist->getId();

        $database->query(
            'INSERT INTO `wishlists_saved`
                         (`user`, `wishlist`)
                  VALUES (:user_id, :wishlist_id)',
            [
                'user_id'     => $userId,
                'wishlist_id' => $wishlistId,
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

        $wishlistId = $this->wishlist->getId();

        $database->query(
            'DELETE FROM `wishlists_saved`
                   WHERE `user`     = :user_id
                     AND `wishlist` = :wishlist_id',
            [
                'user_id'     => $userId,
                'wishlist_id' => $wishlistId,
            ]
        );

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
