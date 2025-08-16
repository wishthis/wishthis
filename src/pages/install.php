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
        \session_destroy();
        unset($_SESSION);
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <?= \file_get_contents(ROOT . '/src/assets/img/logo.svg') ?>

                <h1 class="ui header"><?= $page->title ?></h1>

                <div class="ui segment">
                    <h2 class="ui header"><?= \sprintf(__('Step %d'), $step) ?></h2>

                    <p><?= __('Welcome to the wishthis installer.') ?></p>
                </div>

                <div class="ui segment">
                    <h3 class="ui header"><?= __('Database') ?></h3>

                    <p><?= __('wishthis needs a database to function properly. Please enter your credentials.') ?></p>

                    <form class="ui form" action="<?= Page::PAGE_INSTALL ?>" method="POST">
                        <input type="hidden" name="step" value="<?= $step + 1; ?>" />

                        <div class="ui error message"></div>

                        <div class="field">
                            <label><?= __('Database Engine') ?></label>

                            <select name="DATABASE_ENGINE" class="ui selection dropdown">
                                <option value="mysql"><?= __('MySQL') ?></option>
                                <option value="sqlite"><?= __('SQLite') ?></option>
                            </select>
                        </div>

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
        $directoryCache = ROOT . '/src/cache';

        /**
         * Attempt to create the cache directory, since it doesn't exist by
         * default.
         */
        @\mkdir($directoryCache);

        $prerequisites = [
            [
                'filename'  => __('PHP Version >= 8.1'),
                'icon'      => 'php',
                'condition' => \version_compare(\PHP_VERSION, '8.1', '>='),
                'label'     => __('Compatible'),
            ],
            [
                'filename'  => __('PHP Version < 8.4'),
                'icon'      => 'php',
                'condition' => \version_compare(\PHP_VERSION, '8.4', '<'),
                'label'     => __('Compatible'),
            ],
            [
                'filename'  => 'PHP Extension: Intl',
                'icon'      => 'php',
                'condition' => \extension_loaded('intl'),
                'label'     => __('Activated'),
            ],

            [
                'filename'  => '/src/cache',
                'icon'      => 'folder',
                'condition' => \file_exists($directoryCache) && \is_dir($directoryCache),
                'label'     => __('Exists'),
            ],
            [
                'filename'  => '/src/cache',
                'icon'      => 'folder',
                'condition' => \is_writeable($directoryCache),
                'label'     => __('Writeable'),
            ],

            [
                'filename'  => '/src/config',
                'icon'      => 'folder',
                'condition' => \file_exists(ROOT . '/src/config') && \is_dir(ROOT . '/src/config'),
                'label'     => __('Exists'),
            ],
            [
                'filename'  => '/src/config',
                'icon'      => 'folder',
                'condition' => \is_writeable(ROOT . '/src/config'),
                'label'     => __('Writeable'),
            ],
            [
                'filename'  => '/src/config/config.php',
                'icon'      => 'file',
                'condition' => !\file_exists('/src/config/config.php'),
                'label'     => __('Doesn\'t exist (yet)'),
            ],
        ];

        foreach ($_POST as $key => $value) {
            if ('DATABASE' === \substr($key, 0, 8)) {
                $_SESSION[$key] = $value;
            }
        }
        ?>
        <main>
            <div class="ui hidden divider"></div>
            <div class="ui container">
                <?= \file_get_contents(ROOT . '/src/assets/img/logo.svg') ?>

                <h1 class="ui header"><?= $page->title ?></h1>

                <div class="ui segment">
                    <h2 class="ui header"><?= \sprintf(__('Step %d'), $step) ?></h2>

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
            \mkdir($cacheDirectory);
        }

        /**
         * Config
         */
        $configDirectory  = 'src/config';
        $configPath       = $configDirectory . '/config.php';
        $configSamplePath = $configDirectory . '/config-sample.php';
        $configContents   = \file_get_contents($configSamplePath);

        foreach ($_SESSION as $key => $value) {
            if ('DATABASE' === \substr($key, 0, 8)) {
                $configContents = \preg_replace('/(' . $key . '.+?\').*?(\')/', '$1' . $value . '$2', $configContents);
            }
        }

        \file_put_contents($configPath, $configContents);

        /**
         * Database
         */
        $database = new Database(
            $_SESSION['DATABASE_ENGINE'],
            $_SESSION['DATABASE_HOST'],
            $_SESSION['DATABASE_NAME'],
            $_SESSION['DATABASE_USER'],
            $_SESSION['DATABASE_PASSWORD']
        );
        $database->connect();
        unset($_SESSION);

        $database->disableForeignKeyChecks();

        /**
         * Users
         */
        $currencyFormatter = new \NumberFormatter(DEFAULT_LOCALE, \NumberFormatter::CURRENCY);
        $currencyISO       = $currencyFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);

        $usersTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/users-table-create.sql',
            ROOT,
            $database->engine
        );
        $usersTableCreateSql  = \file_get_contents($usersTableCreatePath);
        $usersTableCreateSql  = \str_replace(
            ['{{DEFAULT_LOCALE}}', '{{CURRENCY_ISO}}'],
            [DEFAULT_LOCALE, $currencyISO],
            $usersTableCreateSql
        );

        $database->query('DROP TABLE IF EXISTS `users`;');
        $database->query($usersTableCreateSql);

        /**
         * Wishlists
         */
        $wishlistsTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/wishlists-table-create.sql',
            ROOT,
            $database->engine
        );
        $wishlistsTableCreateSql  = \file_get_contents($wishlistsTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `wishlists`;');
        $database->query($wishlistsTableCreateSql);

        /**
         * Wishlists Saved
         */
        $wishlistsSavedTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/wishlists-saved-table-create.sql',
            ROOT,
            $database->engine
        );
        $wishlistsSavedTableCreateSql  = \file_get_contents($wishlistsSavedTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `wishlists_saved`;');
        $database->query($wishlistsSavedTableCreateSql);

        /**
         * Wishes
         */
        $wishesTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/wishes-table-create.sql',
            ROOT,
            $database->engine
        );
        $wishesTableCreateSql  = \file_get_contents($wishesTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `wishes`;');
        $database->query($wishesTableCreateSql);

        /**
         * Products
         */
        $productsTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/products-table-create.sql',
            ROOT,
            $database->engine
        );
        $productsTableCreateSql  = \file_get_contents($productsTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `products`;');
        $database->query($productsTableCreateSql);

        /**
         * Options
         */
        $optionsTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/options-table-create.sql',
            ROOT,
            $database->engine
        );
        $optionsTableCreateSql  = \file_get_contents($optionsTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `options`;');
        $database->query($optionsTableCreateSql);

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
        $sessionsTableCreatePath = \sprintf(
            '%1$s/src/sql/%2$s/install/sessions-table-create.sql',
            ROOT,
            $database->engine
        );
        $sessionsTableCreateSql  = \file_get_contents($sessionsTableCreatePath);

        $database->query('DROP TABLE IF EXISTS `sessions`;');
        $database->query($sessionsTableCreateSql);

        $database->enableForeignKeyChecks();
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
