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

    public static function message(string $content = '', string $header = '', string $type = ''): string
    {
        ob_start();

        $containerClasses = array('ui', 'message');
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

    public static function error(string $content, string $header = ''): string
    {
        return self::message($content, $header, 'error');
    }

    public static function warning(string $content, string $header = ''): string
    {
        return self::message($content, $header, 'warning');
    }

    public static function info(string $content, string $header = ''): string
    {
        return self::message($content, $header, 'info');
    }

    public static function success(string $content, string $header = ''): string
    {
        return self::message($content, $header, 'success');
    }

    /**
     * Non-Static
     */
    public string $language = DEFAULT_LOCALE;
    public array $messages  = array();
    public string $link_preview;
    public string $description;

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
            global $page;

            if ('api' !== $page) {
                redirect(Page::PAGE_INSTALL);
            }
        }

        /**
         * Session
         */
        $user = isset($_SESSION['user']->id) ? $_SESSION['user'] : new User();

        /**
         * Power
         */
        if (isset($user->power) && $user->power < $this->power && 0 !== $this->power) {
            redirect(Page::PAGE_POWER . '&required=' . $this->power);
        }

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
         * Update
         */
        $ignoreUpdateRedirect = array(
            'maintenance',
            'login',
            'logout',
            'update',
        );

        if ($options && $options->getOption('updateAvailable') && !in_array($this->name, $ignoreUpdateRedirect)) {
            if (100 === $user->power) {
                redirect(Page::PAGE_UPDATE);
            } else {
                redirect(Page::PAGE_MAINTENANCE);
            }
        }

        /**
         * Redirect
         */
        if ($options && $options->getOption('isInstalled') && isset($_GET)) {
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

        /**
         * Link preview
         */
        $screenshot_filepath = ROOT . '/src/assets/img/screenshots/' . $this->name . '.png';
        $screenshot_url      = 'https://' . $_SERVER['HTTP_HOST'] . '/src/assets/img/screenshots/' . $this->name . '.png';

        if (file_exists($screenshot_filepath)) {
            $this->link_preview = $screenshot_url;
        }
    }

    public function header(): void
    {
        global $locales;
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

            /** Fomantic UI */
            $stylesheetFomantic         = 'semantic/dist/semantic.min.css';
            $stylesheetFomanticModified = filemtime($stylesheetFomantic);
            ?>
            <link rel="stylesheet"
                  type="text/css"
                  href="/<?= $stylesheetFomantic ?>?m=<?= $stylesheetFomanticModified ?>"
            />
            <?php

            /** Default */
            $stylesheetDefault         = 'src/assets/css/default.css';
            $stylesheetDefaultModified = filemtime($stylesheetDefault);
            ?>
            <link rel="stylesheet"
                  type="text/css"
                  href="/<?= $stylesheetDefault ?>?m=<?= $stylesheetDefaultModified ?>"
            />
            <?php

            /** Default (Dark) */
            $stylesheetDefaultDark         = 'src/assets/css/default/dark.css';
            $stylesheetDefaultDarkModified = filemtime($stylesheetDefaultDark);
            ?>
            <link rel="stylesheet"
                  type="text/css"
                  href="/<?= $stylesheetDefaultDark ?>?m=<?= $stylesheetDefaultDarkModified ?>"
            />
            <?php

            /** Page */
            $stylesheetPage = 'src/assets/css/' . $this->name .  '.css';

            if (file_exists($stylesheetPage)) {
                $stylesheetPageModified = filemtime($stylesheetPage);

                ?>
                <link rel="stylesheet"
                      type="text/css"
                      href="/<?= $stylesheetPage ?>?m=<?= $stylesheetPageModified ?>"
                />
                <?php
            }

            /**
             * Inline script
             */
            require ROOT . '/src/assets/js/inline.js.php';

            /** jQuery */
            $scriptjQuery         = 'node_modules/jquery/dist/jquery.min.js';
            $scriptjQueryModified = filemtime($scriptjQuery);
            ?>
            <script defer src="/<?= $scriptjQuery ?>?m=<?= $scriptjQueryModified ?>"></script>
            <?php

            /** Fomantic */
            $scriptFomantic         = 'semantic/dist/semantic.min.js';
            $scriptFomanticModified = filemtime($scriptFomantic);
            ?>
            <script defer src="/<?= $scriptFomantic ?>?m=<?= $scriptFomanticModified ?>"></script>
            <?php

            /** html2canvas */
            $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

            if ($CrawlerDetect->isCrawler()) {
                $scripthtml2canvas1         = 'node_modules/html2canvas/dist/html2canvas.min.js';
                $scripthtml2canvas1Modified = filemtime($scripthtml2canvas1);
                ?>
                <script defer src="/<?= $scripthtml2canvas1 ?>?m=<?= $scripthtml2canvas1Modified ?>"></script>
                <?php

                $scripthtml2canvas2         = 'src/assets/js/html2canvas.js';
                $scripthtml2canvas2Modified = filemtime($scripthtml2canvas2);
                ?>
                <script defer src="/<?= $scripthtml2canvas2 ?>?m=<?= $scripthtml2canvas2Modified ?>"></script>
                <?php
            }

            /** Default */
            $scriptDefault         = 'src/assets/js/default.js';
            $scriptDefaultModified = filemtime($scriptDefault);
            ?>
            <script defer src="/<?= $scriptDefault ?>?m=<?= $scriptDefaultModified ?>"></script>
            <?php

            /** Page */
            $scriptPage = 'src/assets/js/' . $this->name .  '.js';

            if (file_exists($scriptPage)) {
                $scriptPageModified = filemtime($scriptPage);

                ?>
                <script defer src="/<?= $scriptPage ?>?m=<?= $scriptPageModified ?>"></script>
                <?php
            }

            /** plausible */
            ?>
            <script defer
                    data-domain="<?= $_SERVER['HTTP_HOST'] ?>"
                    src="https://plausible.io/js/plausible.js">
            </script>

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
        $user = isset($_SESSION['user']->id) ? $_SESSION['user'] : new User();

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
            if (100 === $user->power) {
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
                    )
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
                    )
                ),
            );
        }

        if (isset($user->power) && 100 === $user->power) {
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

        </body>
        </html>
        <?php
    }

    public function errorDocument(int $statusCode, object $objectNotFound): void
    {
        http_response_code($statusCode);

        $this->header();
        $this->bodyStart();
        $this->navigation();

        $class     = new \ReflectionClass($objectNotFound);
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
