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
    public static function getFromID(int $user_id): self
    {
        global $database;

        $userQuery = $database
        ->query(
            'SELECT *
               FROM `users`
              WHERE `id` = ' . $user_id
        );

        if (false !== $userQuery) {
            $fields = $userQuery->fetch();
            $user   = new User($fields);

            return $user;
        }

        throw new Exception('Unable to find user with ID ' . $user_id . '. Does it exist?');
    }

    public static function generatePassword(string $plainPassword): string
    {
        return sha1($plainPassword);
    }

    /**
     * Private
     */
    private string $locale;

    /**
     * Non-Static
     */
    public int $power                           = 0;
    public ?\Gettext\Translations $translations = null;
    public bool $advertisements                 = false;

    public function __construct(array $fields = array())
    {
        if (!empty($fields)) {
            foreach ($fields as $key => $value) {
                $this->$key = $value;
            }
        }

        /** Set Locale */
        if (!isset($this->locale)) {
            $this->locale = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : DEFAULT_LOCALE;
        }

        $this->setLocale($this->locale);
    }

    /**
     * Set the users locale
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale(string $locale): void
    {
        /** Load Translation */
        $translationFilepath = ROOT . '/translations/' . $locale . '.po';

        if (file_exists($translationFilepath)) {
            $loader             = new \Gettext\Loader\PoLoader();
            $this->translations = $loader->loadFile($translationFilepath);
        }

        /** Set locale */
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Return whether the current user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']->id) && $_SESSION['user']->id >= 1;
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
        ->query(
            'SELECT *
               FROM `wishlists`
              WHERE `user` = ' . $this->id . ';'
        )
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
        ->query(
            'SELECT `ws`.`wishlist`,
                    `w`.`user`,
                    `w`.`hash`
               FROM `wishlists_saved` `ws`
               JOIN `wishlists`       `w`  ON `w`.`id` = `ws`.`wishlist`
              WHERE `ws`.`user` = ' . $this->id . ';'
        )
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

    public function logOut(): void
    {
        /** Destroy session */
        if (isset($_COOKIE[COOKIE_PERSISTENT])) {
            global $database;

            $persistent = $database
            ->query(
                'DELETE FROM `sessions`
                       WHERE `session` = "' . $_COOKIE[COOKIE_PERSISTENT] . '";'
            );
        }

        session_destroy();
        unset($_SESSION);

        /** Delete cookie */
        setcookie(COOKIE_PERSISTENT, '', time() - 3600, '/', getCookieDomain());
    }
}
