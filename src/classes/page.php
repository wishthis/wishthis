<?php

/**
 * page.php
 */

namespace wishthis;

use wishthis\{User, URL};

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
    public string $language = 'en';

    /**
     * __construct
     *
     * @param string $filepath The filepath (__FILE__) of the page.
     * @param string $title    The HTML title of the page.
     */
    public function __construct(string $filepath, public string $title = 'wishthis', public int $power = 0)
    {
        $this->name = pathinfo($filepath, PATHINFO_FILENAME);

        /**
         * Session
         */
        global $user;

        $disableRedirect = array(
            'home',
            'login',
            'register',
            'install'
        );
        if (!isset($_SESSION['user']) && isset($_GET['page']) && !in_array($_GET['page'], $disableRedirect)) {
            header('Location: /?page=login');
            die();
        }

        /**
         * Power
         */
        if ($user && $user->power < $this->power) {
            header('Location: /?page=power&required=' . $this->power);
            die();
        }

        /**
         * Redirect
         */
        $url         = new URL($_SERVER['QUERY_STRING']);
        $redirect_to = $url->getPretty();

        if ($redirect_to && $redirect_to !== $_SERVER['REQUEST_URI']) {
            header('Location: ' . $redirect_to);
            die();
        }
    }

    public function header(): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="<?= $this->language ?>">
        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />

            <link rel="manifest" href="manifest.json" />

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
            echo '<link rel="stylesheet" type="text/css" href="/' . $stylesheetFomantic . '?m=' . $stylesheetFomanticModified . '" />';

            /** Default */
            $stylesheetDefault = 'src/assets/css/default.css';
            $stylesheetDefaultModified = filemtime($stylesheetDefault);
            echo '<link rel="stylesheet" type="text/css" href="/' . $stylesheetDefault . '?m=' . $stylesheetDefaultModified . '" />';

            /** Page */
            $stylesheetPage = 'src/assets/css/' . $this->name .  '.css';

            if (file_exists($stylesheetPage)) {
                $stylesheetPageModified = filemtime($stylesheetPage);

                echo '<link rel="stylesheet" type="text/css" href="/' . $stylesheetPage . '?m=' . $stylesheetPageModified . '" />';
            }

            /**
             * Scripts
             */
            ?>
            <script type="text/javascript">
                var $_GET = JSON.parse('<?= isset($_GET) ? json_encode($_GET) : array() ?>');
            </script>
            <?php

            /** jQuery */
            $scriptjQuery = 'node_modules/jquery/dist/jquery.min.js';
            $scriptjQueryModified = filemtime($scriptjQuery);
            echo '<script defer src="/' . $scriptjQuery . '?m=' . $scriptjQueryModified . '"></script>';

            /** Fomantic */
            $scriptFomantic = 'semantic/dist/semantic.min.js';
            $scriptFomanticModified = filemtime($scriptFomantic);
            echo '<script defer src="/' . $scriptFomantic . '?m=' . $scriptFomanticModified . '"></script>';

            /** Default */
            $scriptDefault = 'src/assets/js/default.js';
            $scriptDefaultModified = filemtime($scriptDefault);
            echo '<script defer src="/' . $scriptDefault . '?m=' . $scriptDefaultModified . '"></script>';

            /** Page */
            $scriptPage = 'src/assets/js/' . $this->name .  '.js';

            if (file_exists($scriptPage)) {
                $scriptPageModified = filemtime($scriptPage);

                echo '<script defer src="/' . $scriptPage . '?m=' . $scriptPageModified . '"></script>';
            }
            ?>

            <title><?= $this->title ?> - wishthis</title>
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

        $pages = array(
            'wishlists' => array(
                'text'      => 'Wishlists',
                'alignment' => 'left',
                'items'     => array(
                    array(
                        'url'  => '/?page=wishlists',
                        'text' => 'My lists',
                        'icon' => 'list',
                    )
                ),
            ),
            'system'    => array(
                'text'      => 'System',
                'icon'      => 'wrench',
                'alignment' => 'right',
                'items'     => array(),
            ),
            'account'   => array(
                'text'      => 'Account',
                'icon'      => 'user circle',
                'alignment' => 'right',
                'items'     => array(),
            ),
        );

        if ($user && $user->isLoggedIn()) {
            if (100 === $user->power) {
                $pages['account']['items'][] = array(
                    'url'  => '/?page=login-as',
                    'text' => 'Login as',
                    'icon' => 'sign out alternate',
                );
            }
            $pages['account']['items'][] = array(
                'url'  => '/?page=logout',
                'text' => 'Logout',
                'icon' => 'sign out alternate',
            );
        } else {
            $pages['account']['items'][] = array(
                'url'  => '/?page=login',
                'text' => 'Login',
                'icon' => 'sign in alternate',
            );
            $pages['account']['items'][] = array(
                'url'  => '/?page=register',
                'text' => 'Register',
                'icon' => 'user plus alternate',
            );
        }

        if (100 === $user->power) {
            $pages['system']['items'][] = array(
                array(
                    'url'  => '/?page=settings',
                    'text' => 'Settings',
                    'icon' => 'cog',
                ),
            );
        }

        global $options;

        if ($options->updateAvailable && $user && 100 === $user->power) {
            $pages['system']['items'][] = array(
                array(
                    'url'  => '/?page=update',
                    'text' => 'Update',
                    'icon' => 'upload',
                ),
            );
        }
        ?>

        <div class="ui attached stackable vertical menu sidebar">
            <div class="ui container">

                <a class="item home" href="/?page=home">
                    <img src="/src/assets/img/logo.svg" alt="wishthis logo" />
                </a>

                <?php foreach ($pages as $page) { ?>
                    <?php foreach ($page['items'] as $item) { ?>
                        <a class="item" href="<?= $item['url'] ?>">
                            <i class="<?= $item['icon'] ?> icon"></i>
                            <?= $item['text'] ?>
                        </a>
                    <?php } ?>
                <?php } ?>

            </div>

            <?= $this->footer() ?>
        </div>

        <div class="pusher">
            <div class="ui attached menu desktop">
                <div class="ui container">
                    <a class="item home" href="/?page=home">
                        <img src="/src/assets/img/logo.svg" />
                    </a>

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
            <div class="ui center aligned container">
                <div class="ui stackable inverted divided equal height stackable grid">

                    <div class="sixteen wide column">
                        <h4 class="ui inverted header">wishthis</h4>

                        <div class="ui inverted link list">
                            <?php
                            global $options;

                            echo 'v' . $options->version;
                            ?>
                        </div>

                        <div class="ui inverted link list">
                            <a class="item" href="https://github.com/grandeljay/wishthis" target="_blank">
                                <i class="big github icon"></i>
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
        ?>
        </div><!-- Pusher -->

        </body>
        </html>
        <?php
    }

    public function messages(array $messages): string
    {
        $html = '';

        foreach ($messages as $message) {
            $html .= $message;
        }

        return $html;
    }
}
