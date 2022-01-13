<?php

/**
 * page.php
 */

namespace wishthis;

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
            ?>

            <script defer src="/node_modules/jquery/dist/jquery.min.js"></script>
            <script defer src="/semantic/dist/semantic.min.js"></script>

            <title><?= $this->title ?> - wishthis</title>
        </head>
        <body>
        <?php
    }

    public function navigation(): void {
        ?>
        <div class="ui attached stackable menu">
            <div class="ui container">
                <a class="item">
                <i class="home icon"></i> Home
                </a>
                <a class="item">
                <i class="grid layout icon"></i> Browse
                </a>
                <a class="item">
                <i class="mail icon"></i> Messages
                </a>
                <div class="ui simple dropdown item">
                More
                <i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item"><i class="edit icon"></i> Edit Profile</a>
                    <a class="item"><i class="globe icon"></i> Choose Language</a>
                    <a class="item"><i class="settings icon"></i> Account Settings</a>
                </div>
                </div>
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
        </body>
        </html>
        <?php
    }
}
