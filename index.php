<?php

/**
 * wishthis - Make a wish
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

\define('VERSION', '1.2.3');
\define('ROOT', __DIR__);
\define('DEFAULT_LOCALE', 'en_GB');

/**
 * Include
 */
require 'vendor/autoload.php';

require_once ROOT . '/src/functions/getCookieDomain.php';
require_once ROOT . '/src/functions/gettext.php';
require_once ROOT . '/src/functions/getWishlistNameSuggestion.php';
require_once ROOT . '/src/functions/redirect.php';

\spl_autoload_register(
    function (string $absoluteNamespace) {
        if (__NAMESPACE__ !== \substr($absoluteNamespace, 0, \strlen(__NAMESPACE__))) {
            return;
        }

        $absoluteNamespace = \str_replace('\\', '/', $absoluteNamespace);

        $filepath = ROOT . '/src/classes/' . $absoluteNamespace . '.php';

        require $filepath;
    }
);

/**
 * Config
 */
$configPath = __DIR__ . '/' . 'src/config/config.php';
$config     = new Config($configPath);
$config->load();

/**
 * Session
 *
 * Has to be setup first, before anything else, so translations can be loaded.
 * The configuration is the only exception, since `loadFromSession` needs the
 * database.
 */
\session_start(
    [
        'name' => 'wishthis',
    ]
);

$user = User::getCurrent();

/**
 * Database
 */
$database = false;
$options  = false;

if (
       \defined('DATABASE_ENGINE')
    && \defined('DATABASE_HOST')
    && \defined('DATABASE_NAME')
    && \defined('DATABASE_USER')
    && \defined('DATABASE_PASSWORD')
) {
    $database = new Database(
        DATABASE_ENGINE,
        DATABASE_HOST,
        DATABASE_NAME,
        DATABASE_USER,
        DATABASE_PASSWORD
    );
    $database->connect();

    /**
     * Options
     */
    $options = new Options($database);

    /**
     * User session
     */
    $user->loadFromSession();
}

/**
 * Language
 */
\Locale::setDefault(DEFAULT_LOCALE);

/** Determine Locale */
$locales = \array_filter(
    \array_map(
        function ($value) {
            $extension = \pathinfo($value, \PATHINFO_EXTENSION);
            $filename  = \pathinfo($value, \PATHINFO_FILENAME);

            if ('po' === $extension) {
                return \pathinfo($value, \PATHINFO_FILENAME);
            }
        },
        \scandir(ROOT . '/translations')
    )
);

$locale = isset($_REQUEST['locale']) ? $_REQUEST['locale'] : \Locale::lookup($locales, $user->getLocale(), false, 'en_GB');

/**
 * Wish
 */
Wish::initialize();

/**
 * Pretty URLs
 */
$url = new URL($_SERVER['REQUEST_URI']);

/**
 * Database Update
 */
if ($options && $options->getOption('isInstalled')) {
    if (-1 === \version_compare($options->version, VERSION)) {
        $options->setOption('updateAvailable', true);
    }
}

/**
 * Page
 */
$requestUri = \parse_url($_SERVER['REQUEST_URI'] ?? '/', \PHP_URL_PATH);

if ('/' !== $requestUri) {
    $requestUri = \rtrim($requestUri, '/');
}

$router = new Router();
$router->delete('/api/wishlists/(?<id>\d+)', [PageControllerApiWishlists::class, 'delete']);
$router->get('/', [PageControllerHome::class, 'default']);
$router->get('/api/blog', [PageControllerApiBlog::class, 'blog']);
$router->get('/api/statistics/all', [PageControllerApiStatistics::class, 'all']);
$router->get('/api/wishlists', [PageControllerApiWishlists::class, 'get']);
$router->get('/api/wishlist/(?<id>\d+)/wishes', [PageControllerApiWishes::class, 'get']);
$router->get('/api/wishlist/(?<hash>[0-9a-f]{40})/wishes', [PageControllerApiWishes::class, 'get']);
$router->get('/blog', [PageControllerBlog::class, 'default']);
$router->get('/blog/post/(?<slug>.+)', [PageControllerBlogPost::class, 'default']);
$router->get('/changelog', [PageControllerChangelog::class, 'default']);
$router->get('/login', [PageControllerLogin::class, 'default']);
$router->get('/logout', [PageControllerLogout::class, 'default']);
$router->get('/profile', [PageControllerProfile::class, 'default']);
$router->get('/register', [PageControllerRegister::class, 'default']);
$router->get('/reset/(?<email>.+)/(?<token>[0-9a-f]+)', [PageControllerReset::class, 'default']);
$router->get('/settings', [PageControllerSettings::class, 'default']);
$router->get('/wishlist/(?<hash>[0-9a-f]{40})', [PageControllerWishlist::class, 'default']);
$router->get('/wishlists', [PageControllerWishlists::class, 'default']);
$router->post('/api/wishlists', [PageControllerApiWishlists::class, 'create']);
$router->post('/login/reset', [PageControllerLogin::class, 'reset']);
$router->post('/login/user', [PageControllerLogin::class, 'login']);
$router->post('/profile', [PageControllerProfile::class, 'update']);
$router->post('/register', [PageControllerRegister::class, 'register']);
$router->post('/reset/(?<email>.+)/(?<token>[0-9a-f]+)', [PageControllerReset::class, 'reset']);
$router->post('/settings', [PageControllerSettings::class, 'save']);
$router->put('/api/wishlists/(?<id>\d+)', [PageControllerApiWishlists::class, 'update']);
$router->resolve($requestUri);
