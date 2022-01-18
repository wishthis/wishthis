<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, Database};

if ($options && $options->getOption('isInstalled')) {
    header('Location: /?page=login');
    die();
}

$page = new page(__FILE__, 'Install');
$page->header();

$step = isset($_POST['step']) ? $_POST['step'] : 1;

switch ($step) {
    case 1:
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header">Install</h1>
                    <h2 class="ui header">Step <?= $step ?></h2>
                    <p>Welcome to the wishthis installer.</p>
                    <p>wishthis needs a database to function properly. Please enter your credentials.</p>

                    <form class="ui form" action="/?page=install" method="post">
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <div class="field">
                            <label>Host</label>
                            <input type="text" name="DATABASE_HOST" placeholder="localhost" value="localhost" />
                        </div>

                        <div class="field">
                            <label>Name</label>
                            <input type="text" name="DATABASE_NAME" placeholder="wishthis" value="wishthis" />
                        </div>

                        <div class="field">
                            <label>Username</label>
                            <input type="text" name="DATABASE_USER" placeholder="root" value="root" />
                        </div>

                        <div class="field">
                            <label>Password</label>
                            <input type="text" name="DATABASE_PASSWORD" />
                        </div>

                        <input class="ui primary button" type="submit" value="Continue" />
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;

    case 2:
        $configDirectory = 'includes/config';
        $configPath = $configDirectory . '/config.php';
        $configSamplePath = $configDirectory . '/config-sample.php';
        $configContents = file_get_contents($configSamplePath);

        foreach ($_POST as $key => $value) {
            if ('DATABASE' === substr($key, 0, 8)) {
                $configContents = preg_replace('/(' . $key . '.+?\').*?(\')/', '$1' . $value . '$2', $configContents);
            }
        }

        file_put_contents($configPath, $configContents);
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header">Install</h1>
                    <h2 class="ui header">Step <?= $step ?></h2>
                    <p>Click Continue to test the database connection.</p>

                    <form class="ui form" action="?page=install" method="post">
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <input class="ui primary button" type="submit" value="Continue" />
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;

    case 3:
        /**
         * Users
         */
        $database->query('CREATE TABLE `users` (
            `id`       int          PRIMARY KEY AUTO_INCREMENT,
            `email`    varchar(64)  NOT NULL UNIQUE,
            `password` varchar(128) NOT NULL INDEX
        );');

        /**
         * Wishlists
         */
        $database->query('CREATE TABLE `wishlists` (
            `id`   int          PRIMARY KEY AUTO_INCREMENT,
            `user` int          NOT NULL,
            `name` varchar(128) NOT NULL,
            FOREIGN KEY (`user`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
        );');

        /**
         * Products
         */
        $database->query('CREATE TABLE `products` (
            `id`       int          NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `wishlist` int          NOT NULL,
            `url`      VARCHAR(255) NOT NULL,
            FOREIGN KEY (`wishlist`)
                REFERENCES `wishlists` (`id`)
                ON DELETE CASCADE
        );');

        /**
         * Options
         */
        $database->query('CREATE TABLE `options` (
            `id`    int          PRIMARY KEY AUTO_INCREMENT,
            `key`   varchar(64)  NOT NULL UNIQUE,
            `value` varchar(128) NOT NULL
        );');

        $database->query('INSERT INTO `options`
            (`key`, `value`) VALUES
            ("isInstalled", true)
        ;');
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header">Success</h1>
                    <p><a class="ui primary button" href="/?page=register">Login</a></p>
                </div>
            </div>
        </main>
        <?php
        break;
}
