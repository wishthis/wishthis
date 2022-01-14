<?php

/**
 * page.php
 */

namespace wishthis;

use wishthis\User;

class Page
{
    private string $language = 'en';

    /**
     * __construct
     *
     * @param string $filepath The filepath (__FILE__) of the page.
     * @param string $title    The HTML title of the page.
     */
    public function __construct(string $filepath, public string $title = 'wishthis')
    {
        $this->name = pathinfo($filepath, PATHINFO_FILENAME);

        /**
         * Session
         */
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

            <?php
            /**
             * Stylesheets
             */

            /** Fomantic UI */
            $stylesheetFomantic = 'semantic/dist/semantic.min.css';
            $stylesheetFomanticModified = filemtime($stylesheetFomantic);
            echo '<link rel="stylesheet" href="' . $stylesheetFomantic . '?m=' . $stylesheetFomanticModified . '" />';

            /** Default */
            $stylesheetDefault = 'includes/assets/css/default.css';
            $stylesheetDefaultModified = filemtime($stylesheetDefault);
            echo '<link rel="stylesheet" href="' . $stylesheetDefault . '?m=' . $stylesheetDefaultModified . '" />';

            /** Page */
            $stylesheetPage = 'includes/assets/css/' . $this->name .  '.css';

            if (file_exists($stylesheetPage)) {
                $stylesheetPageModified = filemtime($stylesheetPage);

                echo '<link rel="stylesheet" href="' . $stylesheetPage . '?m=' . $stylesheetPageModified . '" />';
            }

            /**
             * Scripts
             */

            /** jQuery */
            $scriptjQuery = 'node_modules/jquery/dist/jquery.min.js';
            $scriptjQueryModified = filemtime($scriptjQuery);
            echo '<script defer src="' . $scriptjQuery . '?m=' . $scriptjQueryModified . '"></script>';

            /** Fomantic */
            $scriptFomantic = 'semantic/dist/semantic.min.js';
            $scriptFomanticModified = filemtime($scriptFomantic);
            echo '<script defer src="' . $scriptFomantic . '?m=' . $scriptFomanticModified . '"></script>';

            /** Default */
            $scriptDefault = 'includes/assets/js/default.js';
            $scriptDefaultModified = filemtime($scriptDefault);
            echo '<script defer src="' . $scriptDefault . '?m=' . $scriptDefaultModified . '"></script>';
            ?>

            <title><?= $this->title ?> - wishthis</title>
        </head>
        <body>
        <?php
    }

    public function navigation(): void
    {
        ?>
        <div class="ui attached stackable menu">
            <div class="ui container">
                <a class="item" href="/?page=home">
                    <i class="home icon"></i> Home
                </a>
                <div class="ui simple dropdown item">
                    Wishlist
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="/?page=wishlist-create">
                            <i class="list icon"></i>
                            Create
                        </a>
                        <a class="item" href="/?page=wishlist-view">
                            <i class="list icon"></i>
                            View
                        </a>
                        <a class="item" href="/?page=wishlist-product-add">
                            <i class="plus square icon"></i>
                            Add product
                        </a>
                    </div>
                </div>
                <div class="ui simple dropdown item">
                    Account
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?php
                        $user = isset($_SESSION['user']) ? new User() : null;

                        if ($user && $user->isLoggedIn()) {
                            ?>
                            <a class="item" href="/?page=logout">
                                <i class="sign out alternate icon"></i>
                                Logout
                            </a>
                            <?php
                        } else {
                            ?>
                            <a class="item" href="/?page=login">
                                <i class="sign in alternate icon"></i>
                                Login
                            </a>
                            <a class="item" href="/?page=register">
                                <i class="user plus icon"></i>
                                Register
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php global $options; ?>
                <?php if ($options->updateAvailable && $user && $user->isLoggedIn()) { ?>
                    <a class="item" href="/?page=update">
                        <i class="upload icon"></i> Update
                    </a>
                <?php } ?>
                <div class="right item">
                    <div class="ui input"><input type="text" placeholder="Search..."></div>
                </div>
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
                <div class="sixteen wide column">
                    <h4 class="ui inverted header">wishthis</h4>

                    <div class="ui inverted link list">
                        <a class="item" href="https://github.com/grandeljay/wishthis" target="_blank"><i class="big github icon"></i></a>
                    </div>
                </div>
            </div>
            </div>
        </div>

        </body>
        </html>
        <?php
    }
}
