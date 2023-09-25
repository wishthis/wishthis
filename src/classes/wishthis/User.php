<?php

/**
 * A wishthis user.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class User
{
    public static function getFromID(int $user_id): self
    {
        global $database;

        $userQuery = $database
        ->query(
            'SELECT *
               FROM `users`
              WHERE `id` = :user_id',
            array(
                'user_id' => $user_id,
            )
        );

        if (false !== $userQuery) {
            $fields = $userQuery->fetch();
            $user   = new User($fields);

            return $user;
        }

        throw new \Exception('Unable to find user with ID ' . $user_id . '. Does it exist?');
    }

    public static function passwordToHash(string $plainPassword): string
    {
        return sha1($plainPassword);
    }

    public static function getCurrent(): self
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = new self();
        }

        $user = $_SESSION['user'];

        return $user;
    }

    /**
     * The users unique ID.
     *
     * @var int
     */
    private int $id;

    /**
     * The users unique email address. They are not verified and may be made
     * up.
     *
     * @var string
     */
    private string $email;

    /**
     * The users hashed password.
     *
     * @var string
     */
    private string $password;

    /**
     * The users password reset token.
     *
     * @var string
     */
    private string $password_reset_token;

    /**
     * A unix timestamp indicating until when the users password reset token is
     * valid.
     *
     * @var int
     */
    private int $password_reset_valid_until;

    /**
     * A unix timestamp of when the user has logged in last.
     *
     * @var int
     */
    private int $last_login;

    /**
     * The users power. Administrator have 100, users 1 and unregistered guests
     * 0.
     *
     * @var int
     */
    private int $power = 0;

    /**
     * The users birthdate, formatted as `YYYY-MM-DD`.
     *
     * @var string
     */
    private string $birthdate;

    /**
     * More accurately, this is the users locale (e. g. `en_GB`).
     *
     * @var string
     */
    private string $language;

    /**
     * The users currency (e. g. `EUR`).
     *
     * @var string
     */
    private string $currency;

    /**
     * The users first name.
     *
     * @var string
     */
    private string $name_first;

    /**
     * The users last name.
     *
     * @var string
     */
    private string $name_last;

    /**
     * The users nick name.
     *
     * @var string
     */
    private string $name_nick;

    /**
     * The users preferred release channel. Usually `stable` or
     * `release-candidate` but can also be unset if not defined.
     *
     * @var string
     */
    private string $channel;

    /**
     * Whether the user consented to seeing advertisements.
     *
     * @var bool
     */
    private bool $advertisements = false;

    /**
     * Non-Static
     */
    public ?\Gettext\Translations $translations = null;

    public function __construct(array $fields = array())
    {
        if (!empty($fields)) {
            $this->id                         = $fields['id'];
            $this->email                      = $fields['email'];
            $this->password                   = $fields['password'];
            $this->password_reset_token       = $fields['password_reset_token'] ?? '';
            $this->password_reset_valid_until = \strtotime($fields['password_reset_valid_until']);
            $this->last_login                 = \strtotime($fields['last_login']);
            $this->power                      = $fields['power'];
            $this->birthdate                  = $fields['birthdate'] ?? '';
            $this->language                   = $fields['language'];
            $this->currency                   = $fields['currency'];
            $this->name_first                 = $fields['name_first'] ?? '';
            $this->name_last                  = $fields['name_last'] ?? '';
            $this->name_nick                  = $fields['name_nick'] ?? '';
            $this->channel                    = $fields['channel'] ?? '';
            $this->advertisements             = $fields['advertisements'];
        }

        /** Set Language */
        if (!isset($this->language)) {
            $this->language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : DEFAULT_LOCALE;
        }

        $this->setLocale($this->language);
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
        } else {
            trigger_error('Unable to find translations for ' . $locale . ', defaulting to ' . DEFAULT_LOCALE . '.', E_USER_NOTICE);
        }

        /** Set locale */
        $this->language = $locale;
    }

    public function getLocale(): string
    {
        return $this->language ?? DEFAULT_LOCALE;
    }

    /**
     * Set the user currency
     *
     * @param string $locale
     *
     * @return void
     */
    public function setCurrency(string $locale): void
    {
        $this->currency = $locale;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Return whether the current user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return isset($this->id) && $this->id >= 1;
    }

    /**
     * Returns a list of the users wishlists.
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
        global $database;

        $wishlists = array();

        if (!$this->isLoggedIn()) {
            return $wishlists;
        }

        $result = $database
        ->query(
            '  SELECT `wishlists_saved`.`wishlist`,
                      `wishlists`.`user`,
                      `wishlists`.`hash`
                 FROM `wishlists_saved`
            LEFT JOIN `wishlists` ON `wishlists`.`id` = `wishlists_saved`.`wishlist`
                WHERE `wishlists_saved`.`user` = :user_id;',
            array(
                'user_id' => $this->id,
            )
        )
        ->fetchAll(\PDO::FETCH_ASSOC);

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

    /**
     * Attempts to log in the user. Return whether it was successful or not.
     *
     * @return bool Whether the log in was successful.
     */
    public function logIn(string $email = '', string $password = '', bool $user_login_is_persistent = false): bool
    {
        $database = new Database(
            DATABASE_HOST,
            DATABASE_NAME,
            DATABASE_USER,
            DATABASE_PASSWORD
        );
        $database->connect();

        $login_was_successful = false;

        if ('' === $email && '' === $password && isset($this->email, $this->password)) {
            $email    = $this->email;
            $password = $this->password;
        }

        /**
         * Update the `last_login` column before fetching the user, so it's up
         * to date for the session and later usage.
         *
         * If this fails, we are assuming the user credentials are wrong or that
         * the user does not exist.
         */
        $update_last_login = $database
        ->query(
            'UPDATE `users`
                SET `last_login` = NOW()
              WHERE `email`      = :user_email
                AND `password`   = :user_password;',
            array(
                'user_email'    => $email,
                'user_password' => $password,
            )
        );

        /**
         * Updating the `last_login` column in the database has failed and we
         * are now assuming that the credentials are wrong or that the user does
         * not exist.
         */
        if (false === $update_last_login) {
            return $login_was_successful;
        }

        /**
         * The credentials seem fine, so we are fetching the user fields now.
         */
        $user_database_fields = $database
        ->query(
            'SELECT *
               FROM `users`
              WHERE `email`      = :user_email
                AND `password`   = :user_password;',
            array(
                'user_email'    => $email,
                'user_password' => $password,
            )
        )
        ->fetch();

        /**
         * Create a `User` object instance and assign it for later use.
         */
        if (\is_array($user_database_fields)) {
            $this->__construct($user_database_fields);

            $_SESSION['user'] = $this;

            $login_was_successful = true;
        }

        /**
         * Make the session persist
         */
        if ($user_login_is_persistent) {
            /** Cookie options */
            $sessionLifetime = 2592000 * 4; // 4 Months
            $sessionExpires  = time() + $sessionLifetime;
            $sessionIsDev    = defined('ENV_IS_DEV') && ENV_IS_DEV || '127.0.0.1' === $_SERVER['REMOTE_ADDR'];
            $sessionOptions  = array(
                'domain'   => getCookieDomain(),
                'expires'  => $sessionExpires,
                'httponly' => true,
                'path'     => '/',
                'samesite' => 'None',
                'secure'   => !$sessionIsDev,
            );

            /** Set cookie */
            setcookie(COOKIE_PERSISTENT, session_id(), $sessionOptions);

            $database->query(
                'INSERT INTO `sessions` (
                    `user`,
                    `session`,
                    `expires`
                ) VALUES (
                    :user_id,
                    :session_id,
                    :session_expires
                );',
                array(
                    'user_id'         => $this->id,
                    'session_id'      => session_id(),
                    'session_expires' => date('Y-m-d H:i:s', $sessionExpires),
                )
            );
        }

        return $login_was_successful;
    }

    public function logOut(): void
    {
        /** Destroy session */
        if (isset($_COOKIE[COOKIE_PERSISTENT])) {
            global $database;

            $database
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

    public function delete(): void
    {
        global $database;

        $database->query(
            'DELETE FROM `users`
                   WHERE `id` = :user_id',
            array(
                'user_id' => $this->id,
            )
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNameFirst(): string
    {
        return $this->name_first;
    }

    public function setNameFirst(string $nameFirst): void
    {
        $this->name_first = $nameFirst;
    }

    public function getNameLast(): string
    {
        return $this->name_last;
    }

    public function setNameLast(string $nameLast): void
    {
        $this->name_last = $nameLast;
    }

    public function getNameNick(): string
    {
        return $this->name_nick;
    }

    public function setNameNick(string $nameNick): void
    {
        $this->name_nick = $nameNick;
    }

    public function getEmail(): string
    {
        return $this->email ?? '';
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getAdvertisements(): bool
    {
        return $this->advertisements;
    }

    public function setAdvertisements(bool $advertisements): void
    {
        $this->advertisements = $advertisements;
    }
}
