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
    /**
     * Static
     */
    public static function generatePassword(string $plainPassword): string
    {
        return sha1($plainPassword);
    }

    /**
     * Non-Static
     */
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

        $this->locale = \Locale::acceptFromHttp(
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? DEFAULT_LOCALE
        );

        if (!isset($this->id)) {
            return null;
        }

        global $database;

        $user = $database
        ->query('SELECT *
                   FROM `users`
                  WHERE `id` = ' . $this->id . ';')
        ->fetch();

        foreach ($user as $key => $value) {
            $this->$key = $value;
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

        $wishlists = $database
        ->query('SELECT *
                   FROM `wishlists`
                  WHERE `user` = ' . $this->id . ';')
        ->fetchAll();

        return $wishlists;
    }

    public function getSavedWishlists(): array
    {
        global $database;

        $wishlists = array();

        if (!$this->isLoggedIn()) {
            return $wishlists;
        }

        $result = $database
        ->query('SELECT `ws`.`wishlist`,
                        `w`.`user`,
                        `w`.`hash`
                   FROM `wishlists_saved` `ws`
                   JOIN `wishlists`       `w`  ON `w`.`id` = `ws`.`wishlist`
                  WHERE `ws`.`user` = ' . $this->id . ';')
        ->fetchAll();

        if ($result) {
            $wishlists = $result;
        }

        return $wishlists;
    }

    public function getDisplayName(): string
    {
        return $this->name_nick
            ?: $this->name_first
            ?: $this->email;
    }
}
