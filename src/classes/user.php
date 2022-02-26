<?php

/**
 * user.php
 *
 * A wishthis user.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class User
{
    public int $id;
    public int $power = 0;

    public function __construct(int $id = -1)
    {
        if (-1 === $id) {
            if (isset($_SESSION['user']['id'])) {
                $this->id = $_SESSION['user']['id'];
            }
        } else {
            $this->id = $id;
        }

        if (!isset($this->id)) {
            return null;
        }

        global $database;

        $user = $database
        ->query('SELECT * FROM `users`
                  WHERE `id` = ' . $this->id . ';')
        ->fetch();

        $this->power = $user['power'];
    }

    /**
     * Return whether the current user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Returns a list of the users wishlists.
     * Defaults to the currently logged in user.
     *
     * @return array
     */
    public function getWishlists(): array
    {
        global $database;

        $wishlists = $database->query(
            'SELECT *
             FROM wishlists
             WHERE user = ' . $this->id . ';'
        )->fetchAll();

        return $wishlists;
    }

    /**
     * Returns a list of wishes for a given wishlist.
     *
     * @param  int   $wishlist
     *
     * @return array
     */
    public function getWishes(int $wishlist): array
    {
        global $database;

        $wishes = $database->query(
            'SELECT *
             FROM wishes
             WHERE wishlist = ' . $wishlist . ';'
        )->fetchAll();

        return $wishes;
    }
}
