<?php

namespace wishthis;

class Wishthis
{
    public const VERSION           = '1.0.0';
    public const DEFAULT_LOCALE    = 'en_GB';
    public const COOKIE_PERSISTENT = 'wishthis_persistent';

    private static array $configuration;
    private static Database $database;
    private static Options $options;
    private static array $locales;

    public static function initialise(): void
    {
        self::initialiseAutoloading();
        self::initialiseFunctions();
        self::initialiseConfiguration();
        self::initialiseSession();
        self::initialiseDatabase();
        self::initialiseOptions();
        self::initialiseLocale();
        self::initialiseUser();
        self::initialiseUpdate();

        Wish::initialize();

        self::renderPage();
    }

    private static function initialiseAutoloading(): void
    {
        spl_autoload_register(
            function (string $absolute_namespace) {
                if (__NAMESPACE__ !== substr($absolute_namespace, 0, strlen(__NAMESPACE__))) {
                    return;
                }

                $filepath = './src/classes/' . $absolute_namespace . '.php';

                require $filepath;
            }
        );
    }

    private static function initialiseFunctions(): void
    {
        require './vendor/autoload.php';

        $include = new \Grandel\IncludeDirectory('./src/functions');
    }

    private static function initialiseConfiguration(): void
    {
        $filepath_config = './src/config/config.php';

        require $filepath_config;
    }

    private static function initialiseSession(): void
    {
        session_start(
            array(
                'name' => 'wishthis',
            )
        );

        if (!isset($_SESSION['user']->email)) {
            $_SESSION['user'] = new User();
        }
    }

    private static function initialiseDatabase(): void
    {
        if (
               defined('DATABASE_HOST')
            && defined('DATABASE_NAME')
            && defined('DATABASE_USER')
            && defined('DATABASE_PASSWORD')
        ) {
            self::$database = new Database(
                DATABASE_HOST,
                DATABASE_NAME,
                DATABASE_USER,
                DATABASE_PASSWORD
            );
        }
    }

    private static function initialiseOptions(): void
    {
        if (isset(self::$database)) {
            self::$options = new Options(self::$database);
        }
    }

    private static function initialiseLocale(): void
    {
        /** Determine Locale */
        self::$locales = array_filter(
            array_map(
                function (string $filepath) {
                    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
                    $filename  = pathinfo($filepath, PATHINFO_FILENAME);

                    if ('po' === $extension) {
                        return pathinfo($filepath, PATHINFO_FILENAME);
                    }
                },
                scandir('./translations')
            )
        );

        $locale = self::DEFAULT_LOCALE;

        if (isset($_REQUEST['locale'])) {
            $locale = \Locale::lookup(self::$locales, $_REQUEST['locale']);
        }

        if (isset($_SESSION['user']) && method_exists('User', 'getLocale')) {
            $locale = \Locale::lookup(self::$locales, $_SESSION['user']->getLocale());
        }

        /** Set locale */
        \Locale::setDefault($locale);
    }

    public static function getLocales(): array
    {
        return self::$locales;
    }

    private static function initialiseUser(): void
    {
        if (isset($_COOKIE[self::COOKIE_PERSISTENT]) && self::$database && !$_SESSION['user']->isLoggedIn()) {
            $sessions = self::$database
            ->query(
                'SELECT *
                   FROM `sessions`
                  WHERE `session` = :session;',
                array(
                    'session' => $_COOKIE[self::COOKIE_PERSISTENT],
                )
            )
            ->fetchAll();

            if (false !== $sessions) {
                foreach ($sessions as $session) {
                    $expires = strtotime($session['expires']);

                    if (time() < $expires) {
                        $_SESSION['user'] = User::getFromID($session['user']);

                        break;
                    }
                }
            }
        }
    }

    private static function initialiseUpdate(): void
    {
        if (self::$options && self::$options->getOption('isInstalled')) {
            if (-1 === version_compare(self::$options->version, self::VERSION)) {
                self::$options->setOption('updateAvailable', true);
            }
        }
    }

    private static function renderPage(): void
    {
        if ('cli' === php_sapi_name()) {
            return;
        }

        $page          = isset($_GET['page']) ? $_GET['page'] : 'home';
        $page_filepath = './src/pages/' . $page . '.php';

        if (file_exists($page_filepath)) {
            require $page_filepath;
        } else {
            http_response_code(404);
            ?>
            <h1>Not found</h1>
            <p>The requested URL was not found on this server.</p>
            <?php
            echo $page_filepath;
            die();
        }
    }

    public static function getOptions(): Options
    {
        return self::$options;
    }

    public static function getDatabase(): Database
    {
        return self::$database;
    }
}
