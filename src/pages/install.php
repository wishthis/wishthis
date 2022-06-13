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
    case 1:
        session_destroy();
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header"><?= $page->title ?></h1>
                    <h2 class="ui header"><?= sprintf(__('Step %d'), $step) ?></h2>
                    <p><?= __('Welcome to the wishthis installer.') ?></p>
                    <p><?= __('wishthis needs a database to function properly. Please enter your credentials.') ?></p>

                    <form class="ui form" action="<?= Page::PAGE_INSTALL ?>" method="POST">
                        <input type="hidden" name="install" value="true" />
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <div class="field">
                            <label><?= __('Host') ?></label>
                            <input type="text" name="DATABASE_HOST" placeholder="localhost" value="localhost" />
                        </div>

                        <div class="field">
                            <label><?= __('Name') ?></label>
                            <input type="text" name="DATABASE_NAME" placeholder="wishthis" value="wishthis" />
                        </div>

                        <div class="field">
                            <label><?= __('Username') ?></label>
                            <input type="text" name="DATABASE_USER" placeholder="root" value="root" />
                        </div>

                        <div class="field">
                            <label><?= __('Password') ?></label>
                            <input type="text" name="DATABASE_PASSWORD" />
                        </div>

                        <input class="ui primary button"
                               type="submit"
                               value="<?= __('Continue') ?>"
                               title="<?= __('Continue') ?>"
                        />
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;

    case 2:
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
        $configContents   = str_replace('config-sample.php', 'config.php', file_get_contents($configSamplePath));

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
                    <h1 class="ui header"><?= $page->title ?></h1>
                    <h2 class="ui header"><?= sprintf(__('Step %d'), $step) ?></h2>
                    <p><?= __('Click continue to test the database connection.') ?></p>

                    <form class="ui form" action="<?= Page::PAGE_INSTALL ?>" method="POST">
                        <input type="hidden" name="install" value="true" />
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <input class="ui primary button"
                               type="submit"
                               value="<?= __('Continue') ?>"
                               title="<?= __('Continue') ?>"
                        />
                    </form>
                </div>
            </div>
        </main>
        <?php
        break;

    case 3:
        $database->query('SET foreign_key_checks = 0;');

        /**
         * Users
         */
        $database->query('DROP TABLE IF EXISTS `users`;');
        $database->query('CREATE TABLE `users` (
            `id`                         INT          PRIMARY KEY AUTO_INCREMENT,
            `email`                      VARCHAR(64)  NOT NULL UNIQUE,
            `password`                   VARCHAR(128) NOT NULL,
            `password_reset_token`       VARCHAR(128) NULL     DEFAULT NULL,
            `password_reset_valid_until` DATETIME     NOT NULL DEFAULT NOW(),
            `last_login`                 DATETIME     NOT NULL DEFAULT NOW(),
            `power`                      INT          NOT NULL DEFAULT 0,
            `birthdate`                  DATE         NULL     DEFAULT NULL,
            `locale`                     VARCHAR(5)   NOT NULL DEFAULT "' . DEFAULT_LOCALE . '",
            `name_first`                 VARCHAR(32)  NULL     DEFAULT NULL,
            `name_last`                  VARCHAR(32)  NULL     DEFAULT NULL,
            `name_nick`                  VARCHAR(32)  NULL     DEFAULT NULL,
            `channel`                    VARCHAR(24)  NULL     DEFAULT NULL
        );');
        $database->query('CREATE INDEX `idx_password` ON `users` (`password`);');

        /**
         * Wishlists
         */
        $database->query('DROP TABLE IF EXISTS `wishlists`;');
        $database->query('CREATE TABLE `wishlists` (
            `id`   INT          PRIMARY KEY AUTO_INCREMENT,
            `user` INT          NOT NULL,
            `name` VARCHAR(128) NOT NULL,
            `hash` VARCHAR(128) NOT NULL,
            FOREIGN KEY (`user`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
        );');
        $database->query('CREATE INDEX `idx_hash` ON `wishlists` (`hash`);');

        /**
         * Wishlists Saved
         */
        $database->query('DROP TABLE IF EXISTS `wishlists_saved`;');
        $database->query('CREATE TABLE `wishlists_saved` (
            `id`       INT PRIMARY KEY AUTO_INCREMENT,
            `user`     INT NOT NULL,
            `wishlist` INT NOT NULL,
            FOREIGN KEY (`user`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
        );');
        $database->query('CREATE INDEX `idx_wishlist` ON `wishlists_saved` (`wishlist`);');

        /**
         * Wishes
         */
        $database->query('DROP TABLE IF EXISTS `wishes`;');
        $database->query('CREATE TABLE `wishes` (
            `id`             INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `wishlist`       INT          NOT NULL,
            `title`          VARCHAR(128) NULL     DEFAULT NULL,
            `description`    TEXT         NULL     DEFAULT NULL,
            `image`          VARCHAR(255) NULL     DEFAULT NULL,
            `url`            VARCHAR(255) NULL     DEFAULT NULL,
            `priority`       TINYINT(1)   NULL     DEFAULT NULL,
            `status`         VARCHAR(32)  NULL     DEFAULT NULL,
            `is_purchasable` BOOLEAN      NOT NULL DEFAULT FALSE,
            FOREIGN KEY (`wishlist`)
                REFERENCES `wishlists` (`id`)
                ON DELETE CASCADE
        );');
        $database->query('CREATE INDEX `idx_url` ON `wishes` (`url`);');

        /**
         * Options
         */
        $database->query('DROP TABLE IF EXISTS `options`;');
        $database->query('CREATE TABLE `options` (
            `id`    INT          PRIMARY KEY AUTO_INCREMENT,
            `key`   VARCHAR(64)  NOT NULL UNIQUE,
            `value` VARCHAR(128) NOT NULL
        );');

        $database->query('INSERT INTO `options`
            (`key`, `value`) VALUES
            ("isInstalled", true),
            ("version", "' . VERSION . '")
        ;');

        /**
         * Sessions
         */
        $database->query('DROP TABLE IF EXISTS `sessions`;');
        $database->query('CREATE TABLE `sessions` (
            `id`      INT         PRIMARY KEY AUTO_INCREMENT,
            `user`    INT         NOT NULL,
            `session` VARCHAR(32) NOT NULL,
            FOREIGN KEY (`user`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
        );');
        $database->query('CREATE INDEX `idx_user` ON `sessions` (`user`);');

        $database->query('SET foreign_key_checks = 1;');
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <div class="ui segment">
                    <h1 class="ui header"><?= __('Success') ?></h1>
                    <p>
                        <a class="ui primary button"
                           href="<? Page::PAGE_REGISTER ?>"
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
