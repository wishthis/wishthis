<?php

/**
 * page.php
 */

namespace wishthis;

enum Navigation: int
{
    case Wishlists = 1;
    case Blog      = 2;
    case System    = 3;
    case Settings  = 4;
    case Account   = 5;
    case Login     = 6;
    case Register  = 7;
}

class Page
{
    /**
     * Static
     */
    public const PAGE_API             = '/?page=api';
    public const PAGE_BLOG            = '/?page=blog';
    public const PAGE_CHANGELOG       = '/?page=changelog';
    public const PAGE_HOME            = '/?page=home';
    public const PAGE_INSTALL         = '/?page=install';
    public const PAGE_LOGIN_AS        = '/?page=login-as';
    public const PAGE_LOGIN           = '/?page=login';
    public const PAGE_LOGOUT          = '/?page=logout';
    public const PAGE_MAINTENANCE     = '/?page=maintenance';
    public const PAGE_POST            = '/?page=post';
    public const PAGE_POWER           = '/?page=power';
    public const PAGE_PROFILE         = '/?page=profile';
    public const PAGE_REGISTER        = '/?page=register';
    public const PAGE_SETTINGS        = '/?page=settings';
    public const PAGE_UPDATE          = '/?page=update';
    public const PAGE_WISHLIST        = '/?page=wishlist';
    public const PAGE_WISHLISTS_SAVED = '/?page=wishlists-saved';
    public const PAGE_WISHLISTS       = '/?page=wishlists';

    public static function message(string $content = '', string $header = '', string $type = '', string $class = ''): string
    {
        ob_start();

        $containerClasses = array('ui', 'message', $class);
        $iconClasses      = array('ui', 'icon');

        switch ($type) {
            case 'error':
                $containerClasses[] = 'error icon';
                $iconClasses[]      = 'exclamation triangle';
                break;

            case 'warning':
                $containerClasses[] = 'warning icon';
                $iconClasses[]      = 'exclamation circle';
                break;

            case 'info':
                $containerClasses[] = 'info icon';
                $iconClasses[]      = 'info circle';
                break;

            case 'success':
                $containerClasses[] = 'success icon';
                $iconClasses[]      = 'check circle';
                break;
        }

        $containerClass = implode(' ', $containerClasses);
        $iconClass      = implode(' ', $iconClasses);
        ?>
        <div class="<?= $containerClass ?>">
            <?php if ($type) { ?>
                <i class="<?= $iconClass ?>"></i>
            <?php } ?>

            <div class="content">
                <?php if ($header) { ?>
                    <div class="header"><?= $header ?></div>
                <?php } ?>

                <?php if ($content) { ?>
                    <p><?= $content ?></p>
                <?php } ?>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    public static function error(string $content, string $header = '', string $class = ''): string
    {
        return self::message($content, $header, 'error', $class);
    }

    public static function warning(string $content, string $header = '', string $class = ''): string
    {
        return self::message($content, $header, 'warning', $class);
    }

    public static function info(string $content, string $header = '', string $class = ''): string
    {
        return self::message($content, $header, 'info', $class);
    }

    public static function success(string $content, string $header = '', string $class = ''): string
    {
        return self::message($content, $header, 'success', $class);
    }

    /**
     * The page name. Is used for the HTML `title` and `h1` tags.
     *
     * @var string
     */
    private string $name;
    public string $language = DEFAULT_LOCALE;
    public array $messages  = array();
    public string $link_preview;
    public string $description;

    public array $stylesheets = array();
    public array $scripts     = array();

    /**
     * __construct
     *
     * @param string $filepath The filepath (__FILE__) of the page.
     * @param string $title    The HTML title of the page.
     */
    public function __construct(string $filepath, public string $title = 'wishthis', public int $power = 0)
    {
        global $options;

        $this->name         = pathinfo($filepath, PATHINFO_FILENAME);
        $this->description  = __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.');
        $this->link_preview = 'https://' . $_SERVER['HTTP_HOST'] . '/src/assets/img/link-previews/default.png';

        /**
         * Install
         */
        if (!isset($options) || !$options || !$options->getOption('isInstalled')) {
            if ('api' !== $this->name) {
                redirect(Page::PAGE_INSTALL);
            }
        }

        /**
         * Session
         */
        $user = User::getCurrent();

        /**
         * Login
         */
        if (
               false === $user->isLoggedIn()
            && isset($_GET['page'])
            && 0 !== $this->power
        ) {
            redirect(Page::PAGE_LOGIN);
        }

        /**
         * Power
         */
        if ($user->getPower() < $this->power && 0 !== $this->power) {
            redirect(Page::PAGE_POWER . '&required=' . $this->power);
        }

        /**
         * Update
         */
        $ignoreUpdateRedirect = array(
            'maintenance',
            'login',
            'logout',
            'update',
        );

        if ($options && $options->getOption('updateAvailable') && !in_array($this->name, $ignoreUpdateRedirect)) {
            if (100 === $user->getPower()) {
                redirect(Page::PAGE_UPDATE);
            } else {
                redirect(Page::PAGE_MAINTENANCE);
            }
        }

        /**
         * Redirect
         */
        if ($options && $options->getOption('isInstalled') && isset($_GET) && 'api' !== $this->name) {
            $url = new URL($_SERVER['REQUEST_URI']);

            if ($url->url && false === $url->isPretty()) {
                redirect($url->getPretty());
            }
        }

        /**
         * Locale
         */
        global $locale;

        $this->language = $locale;

        /**
         * Development environment notice
         */
        /**
         * Temporarily deactivate this
         *
         * @see https://wishthis.online/blog/looking-for-testers
         **//*
        if (
               defined('ENV_IS_DEV')
            && true === ENV_IS_DEV
            && 'dev.wishthis.online' === $_SERVER['HTTP_HOST']
        ) {
            $this->messages[] = self::info(
                __('This is the development environment of wishthis. The database will reset every day at around 00:00.'),
                __('Development environment')
            );
        }
        */

        /**
         * Link preview
         */
        $screenshot_filepath = ROOT . '/src/assets/img/screenshots/' . $this->name . '.png';
        $screenshot_url      = 'https://' . $_SERVER['HTTP_HOST'] . '/src/assets/img/screenshots/' . $this->name . '.png';

        if (file_exists($screenshot_filepath)) {
            $this->link_preview = $screenshot_url;
        }

        /**
         * Stylesheets
         */
        $this->stylesheets = array(
            'fomantic-ui' => 'semantic/dist/semantic.min.css',
            'default'     => 'src/assets/css/default.css',
            'dark'        => 'src/assets/css/default/dark.css',
        );

        /**
         * Scripts
         */
        $this->scripts = array(
            'j-query'     => 'node_modules/jquery/dist/jquery.min.js',
            'fomantic-ui' => 'semantic/dist/semantic.min.js',
            'default'     => 'src/assets/js/default.js',
        );

        /** html2canvas */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if ($CrawlerDetect->isCrawler()) {
            $this->scripts['html-2-canvas-1'] = 'node_modules/html2canvas/dist/html2canvas.min.js';
            $this->scripts['html-2-canvas-2'] = 'src/assets/js/html2canvas.js';
        }

        /**
         * Reduce data mode
         */
        $this->messages[] = self::info(
            __('Your device is set to reduce data, some content has been disabled.'),
            __('Reducing data'),
            'reduce-data'
        );
    }

    public function header(): void
    {
        global $locales;

        $user = User::getCurrent();
        ?>
        <!DOCTYPE html>
        <html lang="<?= $this->language ?>">
        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta name="description" content="<?= $this->description ?>" />

            <meta property="og:title" content="<?= $this->title ?>" />
            <meta property="og:type" content="website" />
            <meta property="og:image" content="<?= $this->link_preview ?>" />

            <meta property="og:description" content="<?= $this->description ?>" />
            <meta property="og:locale" content="<?= $this->language ?>" />
            <meta property="og:site_name" content="wishthis" />

            <meta name="twitter:card" content="summary_large_image" />
            <meta property="twitter:domain" content="<?= $_SERVER['HTTP_HOST'] ?>" />
            <meta property="twitter:url" content="https://<?= $_SERVER['HTTP_HOST'] ?>" />
            <meta name="twitter:title" content="<?= $this->title ?>" />
            <meta name="twitter:description" content="<?= $this->description ?>" />
            <meta name="twitter:image" content="<?= $this->link_preview ?>" />

            <?php foreach ($locales as $locale) { ?>
                <?php if ($locale !== $this->language) { ?>
                    <meta property="og:locale:alternate" content="<?= $locale ?>" />
                <?php } ?>
            <?php } ?>

            <link rel="manifest" href="/manifest.json" />
            <?php
            if (defined('CHANNELS') && is_array(CHANNELS)) {
                $channels = CHANNELS;
                $stable   = reset($channels);
                ?>
                <link rel="canonical" href="https://<?= $stable['host'] . $_SERVER['REQUEST_URI'] ?>" />
                <?php
            } else {
                ?>
                <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" />
                <?php
            }
            ?>

            <link rel="apple-touch-icon" sizes="180x180" href="/src/assets/img/favicon/apple-touch-icon.png" />
            <link rel="icon" type="image/png" sizes="32x32" href="/src/assets/img/favicon/favicon-32x32.png" />
            <link rel="icon" type="image/png" sizes="16x16" href="/src/assets/img/favicon/favicon-16x16.png" />
            <link rel="mask-icon" href="/src/assets/img/favicon/safari-pinned-tab.svg" color="#5829bb" />
            <link rel="shortcut icon" href="/src/assets/img/favicon/favicon.ico" />

            <meta name="msapplication-TileColor" content="#ffffff" />
            <meta name="msapplication-config" content="/src/assets/img/favicon/browserconfig.xml" />
            <meta name="theme-color" content="#f4f4f4" />

            <?php
            /**
             * Stylesheets
             */
            $stylesheet_page = 'src/assets/css/' . $this->name .  '.css';

            if (file_exists($stylesheet_page)) {
                $this->stylesheets['page'] = $stylesheet_page;
            }

            foreach ($this->stylesheets as $stylesheet_filepath) {
                $hash = hash_file('crc32', $stylesheet_filepath);
                ?>
                <link rel="stylesheet"
                      type="text/css"
                      href="/<?= $stylesheet_filepath ?>?v=<?= $hash ?>"
                />
                <?php
            }

            /**
             * Scripts
             */
            /** Inline */
            require ROOT . '/src/assets/js/inline.js.php';

            /** Files */
            $script_page = 'src/assets/js/' . $this->name .  '.js';

            if (file_exists($script_page)) {
                $this->scripts['page'] = $script_page;
            }

            foreach ($this->scripts as $script_page) {
                $hash = hash_file('crc32', $script_page);
                ?>
                <script defer
                        type="text/javascript"
                        src="/<?= $script_page ?>?v=<?= $hash ?>">
                </script>
                <?php
            }

            /** plausible */
            if (defined('PLAUSIBLE') && true === PLAUSIBLE) {
                ?>
                <script defer
                        data-domain="<?= $_SERVER['HTTP_HOST'] ?>"
                        src="https://plausible.io/js/plausible.js">
                </script>
                <?php
            }

            /** AdSense */
            $wishthis_hosts = array(
                'wishthis.localhost',
                'wishthis.online',
                'rc.wishthis.online',
                'dev.wishthis.online',
            );
            $CrawlerDetect  = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

            if (
                   in_array($_SERVER['HTTP_HOST'], $wishthis_hosts, true)
                && (true === $user->getAdvertisements() || $CrawlerDetect->isCrawler())
            ) {
                ?>
                <script async
                        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7093703034691766"
                        crossorigin="anonymous">
                </script>
                <?php
            }
            ?>

            <title><?= $this->title ?> - wishthis - <?= __('Make a wish') ?></title>
        </head>
        <?php
    }

    public function bodyStart(): void
    {
        ?>
        <body id="top">
        <?php
    }

    public function navigation(): void
    {
        $user = User::getCurrent();

        $wishlists = Navigation::Wishlists->value;
        $blog      = Navigation::Blog->value;
        $system    = Navigation::System->value;
        $settings  = Navigation::Settings->value;
        $account   = Navigation::Account->value;
        $login     = Navigation::Login->value;
        $register  = Navigation::Register->value;

        $pages = array(
            $blog    => array(
                'text'      => __('Blog'),
                'alignment' => 'left',
                'items'     => array(
                    array(
                        'text' => __('Blog'),
                        'url'  => self::PAGE_BLOG,
                        'icon' => 'rss',
                    ),
                ),
            ),
            $system  => array(
                'text'      => __('System'),
                'icon'      => 'wrench',
                'alignment' => 'right',
                'items'     => array(),
            ),
            $account => array(
                'text'      => __('Account'),
                'icon'      => 'user circle',
                'alignment' => 'right',
                'items'     => array(),
            ),
        );

        if ($user->isLoggedIn()) {
            $pages[$wishlists] = array(
                'text'      => __('Wishlists'),
                'alignment' => 'left',
                'items'     => array(
                    array(
                        'text' => __('My lists'),
                        'url'  => Page::PAGE_WISHLISTS,
                        'icon' => 'list',
                    ),
                    array(
                        'text' => __('Remembered lists'),
                        'url'  => Page::PAGE_WISHLISTS_SAVED,
                        'icon' => 'heart',
                    ),
                ),
            );
        }

        if ($user->isLoggedIn()) {
            $pages[$account]['items'][] = array(
                'text' => __('Profile'),
                'url'  => Page::PAGE_PROFILE,
                'icon' => 'user circle alternate',
            );
            if (100 === $user->getPower()) {
                $pages[$account]['items'][] = array(
                    'text' => __('Login as'),
                    'url'  => Page::PAGE_LOGIN_AS,
                    'icon' => 'sign out alternate',
                );
            }
            $pages[$account]['items'][] = array(
                'text' => __('Logout'),
                'url'  => Page::PAGE_LOGOUT,
                'icon' => 'sign out alternate',
            );
        } else {
            $pages[$login]    = array(
                'text'      => __('Login'),
                'alignment' => 'right',
                'items'     => array(
                    array(
                        'text' => __('Login'),
                        'url'  => Page::PAGE_LOGIN,
                        'icon' => 'sign in alternate',
                    ),
                ),
            );
            $pages[$register] = array(
                'text'      => __('Register'),
                'alignment' => 'right',
                'items'     => array(
                    array(
                        'text' => __('Register'),
                        'url'  => Page::PAGE_REGISTER,
                        'icon' => 'user plus alternate',
                    ),
                ),
            );
        }

        if (100 === $user->getPower()) {
            $pages[$system]['items'][] = array(
                'text' => __('Settings'),
                'url'  => Page::PAGE_SETTINGS,
                'icon' => 'cog',
            );
        }

        ksort($pages);

        if ('home' === $this->name) {
            $logo = file_get_contents(ROOT . '/src/assets/img/logo-animation.svg');
        } else {
            $logo = file_get_contents(ROOT . '/src/assets/img/logo.svg');
        }
        ?>

        <div class="ui attached stackable vertical menu sidebar">
            <div class="ui container">

                <a class="item home" href="<?= Page::PAGE_HOME ?>"><?= $logo ?></a>

                <?php foreach ($pages as $page) { ?>
                    <?php foreach ($page['items'] as $item) { ?>
                        <a class="item" href="<?= $item['url'] ?>">
                            <i class="<?= $item['icon'] ?> icon"></i>
                            <?= $item['text'] ?>
                        </a>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>

        <div class="pusher">
            <div class="ui attached menu desktop">
                <div class="ui container">
                    <a class="item home" href="<?= Page::PAGE_HOME ?>"><?= $logo ?></a>

                    <?php foreach ($pages as $page) { ?>
                        <?php if ('left' === $page['alignment']) { ?>
                            <?php if (count($page['items']) > 1) { ?>
                                <div class="ui simple dropdown item">
                                    <?php if (isset($page['icon'])) { ?>
                                        <i class="<?= $page['icon'] ?> icon"></i>
                                    <?php } ?>

                                    <?= $page['text'] ?>

                                    <i class="dropdown icon"></i>

                                    <div class="menu">
                                        <?php foreach ($page['items'] as $item) { ?>
                                            <a class="item" href="<?= $item['url'] ?>">
                                                <i class="<?= $item['icon'] ?> icon"></i>
                                                <?= $item['text'] ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <?php foreach ($page['items'] as $item) { ?>
                                    <a class="item" href="<?= $item['url'] ?>">
                                        <i class="<?= $item['icon'] ?> icon"></i>
                                        <?= $item['text'] ?>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <div class="right menu">
                        <?php foreach ($pages as $page) { ?>
                            <?php if ('right' === $page['alignment']) { ?>
                                <?php if (count($page['items']) > 1) { ?>
                                    <div class="ui simple dropdown item">
                                        <?php if (isset($page['icon'])) { ?>
                                            <i class="<?= $page['icon'] ?> icon"></i>
                                        <?php } ?>

                                        <?= $page['text'] ?>

                                        <i class="dropdown icon"></i>

                                        <div class="menu">
                                            <?php foreach ($page['items'] as $item) { ?>
                                                <a class="item" href="<?= $item['url'] ?>">
                                                    <i class="<?= $item['icon'] ?> icon"></i>
                                                    <?= $item['text'] ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <?php foreach ($page['items'] as $item) { ?>
                                        <a class="item" href="<?= $item['url'] ?>">
                                            <i class="<?= $item['icon'] ?> icon"></i>
                                            <?= $item['text'] ?>
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="ui attached stackable menu toggle">
                <div class="ui container">
                    <a class="item">
                        <i class="hamburger icon"></i>
                        Menu
                    </a>
                </div>
            </div>

            <div class="ui hidden divider"></div>
            <?php
    }

    public function footer(): void
    {
        ?>
        <div class="ui hidden divider"></div>
        <div class="ui inverted vertical footer segment">
            <div class="ui container">
                <div class="ui stackable inverted divided equal height stackable grid">

                    <div class="six wide column">
                        <h4 class="ui inverted header">wishthis</h4>

                        <div class="ui inverted link list">
                            <div class="item">
                                <i class="code branch icon"></i>
                                <div class="content"><?= VERSION ?></div>
                            </div>

                            <a class="item"
                               href="<?= Page::PAGE_CHANGELOG ?>"
                               title="<?= __('Changelog') ?>"
                            >
                                <i class="newspaper icon"></i>
                                <div class="content"><?= __('Changelog') ?></div>
                            </a>
                        </div>
                    </div>

                    <div class="five wide column">
                        <h4 class="ui inverted header"><?= __('Contribute') ?></h4>

                        <div class="ui inverted link list">
                            <a class="item"
                               href="https://github.com/grandeljay/wishthis"
                               target="_blank"
                               title="<?= __('GitHub') ?>"
                            >
                                <i class="github icon"></i>
                                <div class="content"><?= __('GitHub') ?></div>
                            </a>

                            <a class="item"
                               href="https://www.transifex.com/wishthis/wishthis/"
                               target="_blank"
                               title="<?= __('Transifex') ?>"
                            >
                                <i class="language icon"></i>
                                <div class="content"><?= __('Transifex') ?></div>
                            </a>
                        </div>
                    </div>

                    <div class="five wide column">
                        <h4 class="ui inverted header"><?= __('Contact') ?></h4>

                        <div class="ui inverted link list">
                            <a class="item"
                               href="https://matrix.to/#/#wishthis:matrix.org"
                               target="_blank"
                               title="<?= __('Matrix') ?>"
                            >
                                <i class="comment dots icon"></i>
                                <div class="content"><?= __('Matrix') ?></div>
                            </a>

                            <a class="item"
                               href="https://discord.gg/WrUXnpNyza"
                               target="_blank"
                               title="<?= __('Discord') ?>"
                            >
                                <i class="discord icon"></i>
                                <div class="content"><?= __('Discord') ?></div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }

    public function bodyEnd(): void
    {
        $this->footer();
        ?>
        </div><!-- .pusher -->

        <noscript>
            <dialog open class="ui active dimmer">
                <div class="ui modal">
                    <div class="header">
                        <?= __('JavaScript is disabled') ?>
                    </div>
                    <div class="scrolling content">
                        <div class="description">
                            <div class="ui header">
                                <p>
                                    <?php
                                    printf(
                                        /** TRANSLATORS: %s: the current year */
                                        __('Welcome to the year %s'),
                                        date('Y')
                                    );
                                    ?>
                                </p>
                            </div>

                            <p><?= __('I get it, websites track your every move these days and companies keep coming up with more genius hacks to monetise you.') ?></p>
                            <p><?= __('But the good news is, wishthis aims to be different. It aims to be transparent and let the user stay in control. Unlike many companies just making claims about being secure and protecting your privacy, wishthis is entirely open source, allowing anybody to simply look up what it does and if they are okay with it. For people who aren\'t familiar with my tech stack and aren\'t able to lookup and understand the wishthis source code: "trust me".') ?></p>

                            <p><?= __('I\'m joking - please remain critical, especially for closed source and/or commercial software. At least you can ask somebody to validate the wishthis code for you! Do you have any questions? Message me! (see footer)') ?></p>

                            <p><?= __('wishthis really needs JavaScript to work, please enable it.') ?></p>
                        </div>
                    </div>

                    <form method="dialog" class="actions">
                        <a class="ui primary button" href="/"><?= __('Reload page') ?></a>
                        <button class="ui button"><?= __('Close') ?></button>
                    </form>
                </div>
            </dialog>
        </noscript>
        </body>
        </html>
        <?php
    }

    public function errorDocument(int $statusCode, string $fullyQualifiedClass): void
    {
        http_response_code($statusCode);

        $this->header();
        $this->bodyStart();
        $this->navigation();

        $class     = new \ReflectionClass($fullyQualifiedClass);
        $className = $class->getShortName();
        ?>
        <main>
            <div class="ui container">
                <h1 class="ui header">
                    <?= $statusCode ?>
                    <div class="sub header"><?= sprintf(__('%s not found'), $className) ?></div>
                </h1>

                <?= $this->messages() ?>

                <?php
                switch ($statusCode) {
                    case 404:
                        switch ($className) {
                            case 'Wishlist':
                                echo '<p>' . __('The requested Wishlist was not found and likely deleted by its creator.') . '</p>';
                                break;

                            case 'Wish':
                                echo '<p>' . __('The requested Wish was not found.') . '</p>';
                                break;

                            default:
                                echo '<p>' . sprintf(__('The requested %s was not found.'), $className) . '</p>';
                                break;
                        }
                        break;
                }
                ?>
            </div>
        </main>
        <?php
        $this->footer();
        $this->bodyEnd();

        die();
    }

    public function messages(): string
    {
        $html = '';

        foreach ($this->messages as $message) {
            $html .= $message;
        }

        return $html;
    }
}
