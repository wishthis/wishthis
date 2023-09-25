<?php

/**
 * index.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

if ($options && $options->getOption('isInstalled')) {
    redirect(Page::PAGE_HOME);
}

$page = new Page(__FILE__, __('Install'));
$page->header();
$page->bodyStart();

$step = isset($_POST['step']) ? $_POST['step'] : 1;

switch ($step) {
    /**
     * Test the database credentials
     */
    case 1:
        session_destroy();
        unset($_SESSION);
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <?= file_get_contents(ROOT . '/src/assets/img/logo.svg') ?>

                <h1 class="ui header"><?= $page->title ?></h1>

                <div class="ui segment">
                    <h2 class="ui header"><?= sprintf(__('Step %d'), $step) ?></h2>

                    <p><?= __('Welcome to the wishthis installer.') ?></p>
                </div>

                <div class="ui segment">
                    <h3 class="ui header"><?= __('Database') ?></h3>

                    <p><?= __('wishthis needs a database to function properly. Please enter your credentials.') ?></p>

                    <form class="ui form" action="<?= Page::PAGE_INSTALL ?>" method="POST">
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <div class="ui error message"></div>

                        <div class="equal width fields">
                            <div class="field">
                                <label><?= __('Host') ?></label>
                                <input type="text" name="DATABASE_HOST" placeholder="localhost" value="localhost" />
                            </div>

                            <div class="field">
                                <label><?= __('Name') ?></label>
                                <input type="text" name="DATABASE_NAME" placeholder="wishthis" value="wishthis" />
                            </div>
                        </div>

                        <div class="equal width fields">
                            <div class="field">
                                <label><?= __('Username') ?></label>
                                <input type="text" name="DATABASE_USER" placeholder="root" value="root" />
                            </div>

                            <div class="field">
                                <label><?= __('Password') ?></label>
                                <input type="text" name="DATABASE_PASSWORD" />
                            </div>
                        </div>

                        <div class="inline fields">
                            <input class="ui primary disabled button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                            <button class="ui button" id="database-test" type="button">
                                <?= __('Test connection') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;

    /**
     * Check prerequisites
     */
    case 2:
        $prerequisites = array(
            array(
                'filename'  => __('PHP Version >= 8.1'),
                'icon'      => 'php',
                'condition' => \version_compare(\PHP_VERSION, '8.1', '>='),
                'label'     => __('Compatible'),
            ),
            array(
                'filename'  => __('PHP Version < 8.3'),
                'icon'      => 'php',
                'condition' => \version_compare(\PHP_VERSION, '8.3', '<'),
                'label'     => __('Compatible'),
            ),
            array(
                'filename'  => 'PHP Extension: Intl',
                'icon'      => 'php',
                'condition' => \extension_loaded('intl'),
                'label'     => __('Activated'),
            ),

            array(
                'filename'  => '/src/cache',
                'icon'      => 'folder',
                'condition' => \file_exists(ROOT . '/src/cache') && \is_dir(ROOT . '/src/cache'),
                'label'     => __('Exists'),
            ),
            array(
                'filename'  => '/src/cache',
                'icon'      => 'folder',
                'condition' => \is_writeable(ROOT . '/src/cache'),
                'label'     => __('Writeable'),
            ),

            array(
                'filename'  => '/src/config',
                'icon'      => 'folder',
                'condition' => \file_exists(ROOT . '/src/config') && \is_dir(ROOT . '/src/config'),
                'label'     => __('Exists'),
            ),
            array(
                'filename'  => '/src/config',
                'icon'      => 'folder',
                'condition' => \is_writeable(ROOT . '/src/config'),
                'label'     => __('Writeable'),
            ),
            array(
                'filename'  => '/src/config/config.php',
                'icon'      => 'file',
                'condition' => !\file_exists('/src/config/config.php'),
                'label'     => __('Doesn\'t exist (yet)'),
            ),
        );

        foreach ($_POST as $key => $value) {
            if ('DATABASE' === substr($key, 0, 8)) {
                $_SESSION[$key] = $value;
            }
        }
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <?= file_get_contents(ROOT . '/src/assets/img/logo.svg') ?>

                <h1 class="ui header"><?= $page->title ?></h1>

                <div class="ui segment">
                    <h2 class="ui header"><?= sprintf(__('Step %d'), $step) ?></h2>

                    <p><?= __('Make sure all prerequisites are met or the installation may fail in the next step.') ?></p>
                </div>

                <div class="ui segment">
                    <h3 class="ui header"><?= __('Prerequisites check') ?></h3>

                    <table class="ui celled striped table">
                        <thead>
                            <tr>
                                <th colspan="3"><?= __('Installation prerequisites') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prerequisites as $prerequisite) { ?>
                                <tr class="<?= $prerequisite['condition'] ? 'positive' : 'negative' ?>">
                                    <td>
                                        <i class="<?= $prerequisite['icon'] ?> icon"></i><?= $prerequisite['filename'] ?>
                                    </td>
                                    <td class="collapsing">
                                        <?php if ($prerequisite['condition']) { ?>
                                            <i class="green checkmark icon"></i>
                                        <?php } else { ?>
                                            <i class="red close icon"></i>
                                        <?php } ?>
                                    </td>
                                    <td class="collapsing">
                                        <?= $prerequisite['label'] ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <form class="ui form" action="<?= Page::PAGE_INSTALL ?>" method="POST">
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <div class="ui error message"></div>

                        <div class="inline fields">
                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Install wishthis') ?>"
                                title="<?= __('Install wishthis') ?>"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;
    /**
     * Perform installation
     */
    case 3:
        /**
         * To do: Set absolute sitemap path in robots.txt.
         */

        /**
         * Cache
         */
        $cacheDirectory = 'src/cache';

        if (!file_exists($cacheDirectory)) {
            mkdir($cacheDirectory);
        }

        /**
         * Config
         */
        $configDirectory  = 'src/config';
        $configPath       = $configDirectory . '/config.php';
        $configSamplePath = $configDirectory . '/config-sample.php';
        $configContents   = file_get_contents($configSamplePath);

        foreach ($_SESSION as $key => $value) {
            if ('DATABASE' === substr($key, 0, 8)) {
                $configContents = preg_replace('/(' . $key . '.+?\').*?(\')/', '$1' . $value . '$2', $configContents);
            }
        }

        \file_put_contents($configPath, $configContents);

        /**
         * Database
         */
        $database = new Database(
            $_SESSION['DATABASE_HOST'],
            $_SESSION['DATABASE_NAME'],
            $_SESSION['DATABASE_USER'],
            $_SESSION['DATABASE_PASSWORD']
        );
        $database->connect();
        unset($_SESSION);

        $database->query('SET foreign_key_checks = 0;');

        /**
         * Users
         */
        $currencyFormatter = new \NumberFormatter(DEFAULT_LOCALE, \NumberFormatter::CURRENCY);
        $currencyISO       = $currencyFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);

        $database->query('DROP TABLE IF EXISTS `users`;');
        $database->query(
            'CREATE TABLE `users` (
                `id`                         INT          PRIMARY KEY AUTO_INCREMENT,
                `email`                      VARCHAR(64)  NOT NULL UNIQUE,
                `password`                   VARCHAR(128) NOT NULL,
                `password_reset_token`       VARCHAR(128) NULL     DEFAULT NULL,
                `password_reset_valid_until` DATETIME     NOT NULL DEFAULT NOW(),
                `last_login`                 DATETIME     NOT NULL DEFAULT NOW(),
                `power`                      INT          NOT NULL DEFAULT 1,
                `birthdate`                  DATE         NULL     DEFAULT NULL,
                `language`                   VARCHAR(5)   NOT NULL DEFAULT "' . DEFAULT_LOCALE . '",
                `currency`                   VARCHAR(3)   NOT NULL DEFAULT "' . $currencyISO . '",
                `name_first`                 VARCHAR(32)  NULL     DEFAULT NULL,
                `name_last`                  VARCHAR(32)  NULL     DEFAULT NULL,
                `name_nick`                  VARCHAR(32)  NULL     DEFAULT NULL,
                `channel`                    VARCHAR(24)  NULL     DEFAULT NULL,
                `advertisements`             TINYINT(1)   NOT NULL DEFAULT 0,

                INDEX `idx_password` (`password`)
            );'
        );

        /**
         * Wishlists
         */
        $database->query('DROP TABLE IF EXISTS `wishlists`;');
        $database->query(
            'CREATE TABLE `wishlists` (
                `id`                INT          PRIMARY KEY AUTO_INCREMENT,
                `user`              INT          NOT NULL,
                `name`              VARCHAR(128) NOT NULL,
                `hash`              VARCHAR(128) NOT NULL,
                `notification_sent` TIMESTAMP        NULL DEFAULT NULL,

                INDEX `idx_hash` (`hash`),
                CONSTRAINT `FK_wishlists_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
            );'
        );

        /**
         * Wishlists Saved
         */
        $database->query('DROP TABLE IF EXISTS `wishlists_saved`;');
        $database->query(
            'CREATE TABLE `wishlists_saved` (
                `id`       INT PRIMARY KEY AUTO_INCREMENT,
                `user`     INT NOT NULL,
                `wishlist` INT NOT NULL,

                INDEX `idx_wishlist` (`wishlist`),
                CONSTRAINT `FK_wishlists_saved_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
            );'
        );

        /**
         * Wishes
         */
        $database->query('DROP TABLE IF EXISTS `wishes`;');
        $database->query(
            'CREATE TABLE `wishes` (
                `id`             INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `wishlist`       INT          NOT NULL,
                `title`          VARCHAR(128) NULL     DEFAULT NULL,
                `description`    TEXT         NULL     DEFAULT NULL,
                `image`          TEXT         NULL     DEFAULT NULL,
                `url`            VARCHAR(255) NULL     DEFAULT NULL,
                `priority`       TINYINT(1)   NULL     DEFAULT NULL,
                `status`         VARCHAR(32)  NULL     DEFAULT NULL,
                `is_purchasable` BOOLEAN      NOT NULL DEFAULT FALSE,
                `edited`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                INDEX `idx_url` (`url`),
                CONSTRAINT `FK_wishes_wishlists` FOREIGN KEY (`wishlist`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE
            );'
        );

        /**
         * Products
         */
        $database->query(
            'CREATE TABLE `products` (
                `wish`  INT   NOT NULL PRIMARY KEY,
                `price` FLOAT NULL     DEFAULT NULL,

                CONSTRAINT `FK_products_wishes` FOREIGN KEY (`wish`) REFERENCES `wishes` (`id`) ON DELETE CASCADE
            );'
        );

        /**
         * Options
         */
        $database->query('DROP TABLE IF EXISTS `options`;');
        $database->query(
            'CREATE TABLE `options` (
                `id`    INT          PRIMARY KEY AUTO_INCREMENT,
                `key`   VARCHAR(64)  NOT NULL UNIQUE,
                `value` VARCHAR(128) NOT NULL
            );'
        );

        $database->query(
            'INSERT INTO
                `options` (`key`, `value`)
            VALUES
                ("isInstalled", true),
                ("version", "' . VERSION . '")
            ;'
        );

        /**
         * Sessions
         */
        $database->query(
            'CREATE TABLE `sessions` (
                `id`      INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `user`    INT         NOT NULL,
                `session` VARCHAR(32) NOT NULL,
                `expires` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP(),

                INDEX `idx_user` (`session`),
                CONSTRAINT `FK_sessions_users` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
            );'
        );

        $database->query('SET foreign_key_checks = 1;');
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header"><?= __('Success') ?></h1>
                    <p>
                        <a class="ui primary button"
                           href="<?= Page::PAGE_REGISTER ?>"
                           title="<?= __('Register') ?>"
                        >
                            <?= __('Register') ?>
                        </a>
                    </p>
                </div>
            </div>
        </main>
        <?php
        break;
}
