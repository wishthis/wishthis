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
     *
     * @return string
     */
    public static function message(string $content = '', string $header = '', string $type = ''): string
    {
        ob_start();

        $containerClasses = array('ui', 'message');
        $iconClasses      = array('ui', 'icon');

        switch ($type) {
            case 'error':
                $containerClasses[] = 'error icon';
                $iconClasses[] = 'exclamation triangle';
                break;

            case 'warning':
                $containerClasses[] = 'warning icon';
                $iconClasses[] = 'exclamation circle';
                break;

            case 'info':
                $containerClasses[] = 'info icon';
                $iconClasses[] = 'info circle';
                break;

            case 'success':
                $containerClasses[] = 'success icon';
                $iconClasses[] = 'check circle';
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
        $this->name         = pathinfo($filepath, PATHINFO_FILENAME);
        $this->description  = __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.');
        $this->link_preview = 'https://' . $_SERVER['HTTP_HOST'] . '/src/assets/img/link-previews/default.png';

        /**
         * Session
         */
        global $user, $options;

        $ignorePower = array(
            'home',
            'blog',
            'post',
            'install',
            'login',
            'maintenance',
            'register',
            'changelog',
            'wishlist',
        );
        if (
               !isset($_SESSION['user'])
            && isset($_SESSION['_GET']['page'])
            && !in_array($_SESSION['_GET']['page'], $ignorePower)
        ) {
            redirect('/?page=login');
        }

        /**
         * Power
         */
        if (isset($user->power) && $user->power < $this->power) {
            redirect('/?page=power&required=' . $this->power);
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
                redirect('/?page=update');
            } else {
                redirect('/?page=maintenance');
            }
        }

        /**
         * Redirect
         */
        if ($options && $options->getOption('isInstalled') && isset($_SESSION['_GET'])) {
            $url = new URL(http_build_query($_SESSION['_GET']));

            if (false === $url->isPretty()) {
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
            $stylesheetFomantic = 'semantic/dist/semantic.min.css';
            $stylesheetFomanticModified = filemtime($stylesheetFomantic);
            ?>
            <link rel="stylesheet"
                  type="text/css"
                  href="/<?= $stylesheetFomantic ?>?m=<?= $stylesheetFomanticModified ?>"
            />
            <?php

            /** Default */
            $stylesheetDefault = 'src/assets/css/default.css';
            $stylesheetDefaultModified = filemtime($stylesheetDefault);
            ?>
            <link rel="stylesheet"
                  type="text/css"
                  href="/<?= $stylesheetDefault ?>?m=<?= $stylesheetDefaultModified ?>"
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
             * Scripts
             */
            ?>
            <script type="text/javascript">
                var locale                  = '<?= str_replace('_', '-', $this->language) ?>';
                var $_GET                   = JSON.parse('<?= isset($_SESSION['_GET']) ? json_encode($_SESSION['_GET']) : json_encode(array()) ?>');
                var wish_status_temporary   = '<?= Wish::STATUS_TEMPORARY ?>';
                var wish_status_unavailable = '<?= Wish::STATUS_UNAVAILABLE ?>';
                var wish_status_fulfilled   = '<?= Wish::STATUS_FULFILLED ?>';
                var text                    = {
                    wishlist_no_selection : '<?= __('No wishlist selected.') ?>',

                    modal_error_title     : '<?= __('Error') ?>',
                    modal_failure_title   : '<?= __('Failure') ?>',
                    modal_failure_content : '<?= __('The server did not confirm that the action was successful.') ?>',
                    modal_failure_approve : '<?= __('Thanks for nothing') ?>',
                    modal_warning_approve : '<?= __('Understood') ?>',
                    modal_success_title   : '<?= __('Success') ?>',

                    modal_wishlist_warning_approve : '<?= __('Close this tab') ?>',
                    modal_wishlist_warning_deny    : '<?= __('Show wishlist anyway') ?>',
                    modal_wishlist_delete_title    : '<?= __('Really delete?') ?>',
                    modal_wishlist_delete          : '<?= sprintf(__('Do you really want to delete the wishlist %s?'), '<strong>WISHLIST_NAME</strong>') ?>',
                    modal_wishlist_delete_approve  : '<?= __('Yes, delete') ?>',
                    modal_wishlist_delete_deny     : '<?= __('No, keep') ?>',

                    modal_wish_delete_title   : '<?= __('Really delete?') ?>',
                    modal_wish_delete         : '<?= __('Would you really like to delete to this wish? It will be gone forever.') ?>',
                    modal_wish_delete_approve : '<?= __('Yes, delete') ?>',
                    modal_wish_delete_deny    : '<?= __('No, keep') ?>',

                    form_profile_password : '<?= __('Passwords must match.') ?>',

                    toast_wishlist_rename : '<?= __('Wishlist successfully renamed.') ?>',
                    toast_wishlist_delete : '<?= __('Wishlist successfully deleted.') ?>',

                    toast_wish_create : '<?= __('Wish successfully created.') ?>',
                    toast_wish_add    : '<?= __('Wish successfully added.') ?>',
                    toast_wish_update : '<?= __('Wish information updated.') ?>',
                    toast_wish_save   : '<?= __("Don't forget to save your changes.") ?>',
                    toast_wish_delete : '<?= __('Wish successfully deleted.') ?>',

                    toast_clipboard_error_title : '<?= __('Error') ?>',
                    toast_clipboard_error       : '<?= __('Unable to copy to clipboard. There is likely a permission issue.') ?>',
                    toast_clipboard_success     : '<?= __('Link copied to clipboard.') ?>',

                    form_prompt_empty                : '<?= __('{name} must have a value') ?>',
                    form_prompt_checked              : '<?= __('{name} must be checked') ?>',
                    form_prompt_email                : '<?= __('{name} must be a valid e-mail') ?>',
                    form_prompt_url                  : '<?= __('{name} must be a valid URL') ?>',
                    form_prompt_regExp               : '<?= __('{name} is not formatted correctly') ?>',
                    form_prompt_integer              : '<?= __('{name} must be an integer') ?>',
                    form_prompt_decimal              : '<?= __('{name} must be a decimal number') ?>',
                    form_prompt_number               : '<?= __('{name} must be set to a number') ?>',
                    form_prompt_is                   : '<?= __('{name} must be "{ruleValue}"') ?>',
                    form_prompt_isExactly            : '<?= __('{name} must be exactly "{ruleValue}"') ?>',
                    form_prompt_not                  : '<?= __('{name} cannot be set to "{ruleValue}"') ?>',
                    form_prompt_notExactly           : '<?= __('{name} cannot be set to exactly "{ruleValue}"') ?>',
                    form_prompt_contain              : '<?= __('{name} cannot contain "{ruleValue}"') ?>',
                    form_prompt_containExactly       : '<?= __('{name} cannot contain exactly "{ruleValue}"') ?>',
                    form_prompt_doesntContain        : '<?= __('{name} must contain "{ruleValue}"') ?>',
                    form_prompt_doesntContainExactly : '<?= __('{name} must contain exactly "{ruleValue}"') ?>',
                    form_prompt_minLength            : '<?= __('{name} must be at least {ruleValue} characters') ?>',
                    form_prompt_length               : '<?= __('{name} must be at least {ruleValue} characters') ?>',
                    form_prompt_exactLength          : '<?= __('{name} must be exactly {ruleValue} characters') ?>',
                    form_prompt_maxLength            : '<?= __('{name} cannot be longer than {ruleValue} characters') ?>',
                    form_prompt_match                : '<?= __('{name} must match {ruleValue} field') ?>',
                    form_prompt_different            : '<?= __('{name} must have a different value than {ruleValue} field') ?>',
                    form_prompt_creditCard           : '<?= __('{name} must be a valid credit card number') ?>',
                    form_prompt_minCount             : '<?= __('{name} must have at least {ruleValue} choices') ?>',
                    form_prompt_exactCount           : '<?= __('{name} must have exactly {ruleValue} choices') ?>',
                    form_prompt_maxCount             : '<?= __('{name} must have {ruleValue} or less choices') ?>',

                    calendar_today   : '<?= _x('Today', 'Calendar') ?>',
                    calendar_now     : '<?= _x('Now', 'Calendar') ?>',
                    calendar_am      : '<?= _x('AM', 'Calendar') ?>',
                    calendar_pm      : '<?= _x('PM', 'Calendar') ?>',
                    calendar_week_no : '<?= _x('Week', 'Calendar') ?>',

                    button_wishlist_save : '<?= __('Save') ?>',
                    button_wishlist_saved : '<?= __('Saved') ?>',
                };
            </script>
            <?php
            /** jQuery */
            $scriptjQuery = 'node_modules/jquery/dist/jquery.min.js';
            $scriptjQueryModified = filemtime($scriptjQuery);
            ?>
            <script defer src="/<?= $scriptjQuery ?>?m=<?= $scriptjQueryModified ?>"></script>
            <?php

            /** Fomantic */
            $scriptFomantic = 'semantic/dist/semantic.min.js';
            $scriptFomanticModified = filemtime($scriptFomantic);
            ?>
            <script defer src="/<?= $scriptFomantic ?>?m=<?= $scriptFomanticModified ?>"></script>
            <?php

            /** Default */
            $scriptDefault = 'src/assets/js/default.js';
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

            <title><?= $this->title ?> - wishthis - Make a wish</title>
        </head>
        <?php
    }

    public function bodyStart(): void
    {
        ?>
        <body>
        <?php
    }

    public function navigation(): void
    {
        $user = new User();

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
                        'url'  => '/?page=blog',
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
                        'url'  => '/?page=wishlists',
                        'icon' => 'list',
                    ),
                    array(
                        'text' => __('Saved lists'),
                        'url'  => '/?page=wishlists-saved',
                        'icon' => 'heart',
                    ),
                ),
            );
        }

        if ($user->isLoggedIn()) {
            $pages[$account]['items'][] = array(
                'text' => __('Profile'),
                'url'  => '/?page=profile',
                'icon' => 'user circle alternate',
            );
            if (100 === $user->power) {
                $pages[$account]['items'][] = array(
                    'text' => __('Login as'),
                    'url'  => '/?page=login-as',
                    'icon' => 'sign out alternate',
                );
            }
            $pages[$account]['items'][] = array(
                'text' => __('Logout'),
                'url'  => '/?page=logout',
                'icon' => 'sign out alternate',
            );
        } else {
            $pages[$login] = array(
                'text'      => __('Login'),
                'alignment' => 'right',
                'items'     => array(
                    array(
                        'text' => __('Login'),
                        'url'  => '/?page=login',
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
                        'url'  => '/?page=register',
                        'icon' => 'user plus alternate',
                    )
                ),
            );
        }

        if (isset($user->power) && 100 === $user->power) {
            $pages[$system]['items'][] = array(
                'text' => __('Settings'),
                'url'  => '/?page=settings',
                'icon' => 'cog',
            );
        }

        ksort($pages);

        $svgLogo = file_get_contents(ROOT . '/src/assets/img/logo.svg');
        ?>

        <div class="ui attached stackable vertical menu sidebar">
            <div class="ui container">

                <a class="item home" href="/?page=home"><?= $svgLogo ?></a>

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
                    <a class="item home" href="/?page=home"><?= $svgLogo ?></a>

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
                                <div class="content">
                                    <?php
                                    global $options;

                                    if (VERSION === $options->version) {
                                        echo 'v' . VERSION;
                                    } else {
                                        echo 'v' . VERSION . ' / ' . 'v' . $options->version;
                                    }
                                    ?>
                                </div>
                            </div>

                            <a class="item"
                               href="/?page=changelog"
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
                               title="<?= __('GitHub repository') ?>"
                            >
                                <i class="github icon"></i>
                                <div class="content"><?= __('GitHub repository') ?></div>
                            </a>

                            <a class="item"
                               href="https://www.transifex.com/wishthis/wishthis/"
                               target="_blank"
                               title="<?= __('Translate') ?>"
                            >
                                <i class="language icon"></i>
                                <div class="content"><?= __('Translate') ?></div>
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
