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
    public static function getFromID(int $user_id): self|false
    {
        $database = Wishthis::getDatabase();

        $userQuery = $database
        ->query(
            'SELECT *
               FROM `users`
              WHERE `id` = :user_id',
            array(
                'user_id' => $user_id,
            )
        );

        if (false === $userQuery) {
            return false;
        }

        $fields = $userQuery->fetch();

        if (false === $fields) {
            return false;
        }

        $user = new User($fields);

        return $user;
    }

    public static function generatePassword(string $plainPassword): string
    {
        return sha1($plainPassword);
    }

    /**
     * Private
     */
    private string $currency;

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
    }

    /**
     * Set the users locale
     *
     * @param string $locale
     *
     * @return void
     */
    // public function setLocale(string $locale): void
    // {
    //     /** Load Translation */
    //     $translationFilepath = './translations/' . $locale . '.po';
    //
    //     if (file_exists($translationFilepath)) {
    //         $loader             = new \Gettext\Loader\PoLoader();
    //         $this->translations = $loader->loadFile($translationFilepath);
    //     } else {
    //         trigger_error('Unable to find translations for ' . $locale . ', defaulting to ' . Wishthis::DEFAULT_LOCALE . '.', E_USER_NOTICE);
    //     }
    //
    //     /** Set locale */
    //     $this->language = $locale;
    // }
    //
    // public function getLocale(): string
    // {
    //     return $this->language ?? Wishthis::DEFAULT_LOCALE;
    // }

    /**
     * Set the user currency
     *
     * @param string $locale
     *
     * @return void
     */
    // public function setCurrency(string $locale): void
    // {
    //     $this->currency = \Locale::getDefault();
    // }
    //
    // public function getCurrency(): string
    // {
    //     return $this->currency;
    // }

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
        $database = Wishthis::getDatabase();

        $wishlists = $database
        ->query(
            'SELECT *
               FROM `wishlists`
              WHERE `user` = :user_id;',
            array(
                'user_id' => $this->id,
            )
        )
        ->fetchAll();

        return $wishlists;
    }

    public function getSavedWishlists(): array
    {
        $database = Wishthis::getDatabase();

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
              WHERE `ws`.`user` = :user_id;',
            array(
                'user_id' => $this->id,
            )
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
            $database = Wishthis::getDatabase();

            $persistent = $database
            ->query(
                'DELETE FROM `sessions`
                       WHERE `session` = :session;',
                array(
                    'session' => $_COOKIE[COOKIE_PERSISTENT],
                )
            );
        }

        session_destroy();
        unset($_SESSION);

        /** Delete cookie */
        setcookie(COOKIE_PERSISTENT, '', time() - 3600, '/', getCookieDomain());
    }
}
