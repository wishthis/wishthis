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

    public function __construct(int $id = -1)
    {
        if (-1 === $id) {
            $this->id = $_SESSION['user']['id'];
        } else {
            $this->id = $id;
        }
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
             WHERE user = ' . $_SESSION['user']['id'] . ';'
        )->fetchAll();

        return $wishlists;
    }
}
