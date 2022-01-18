<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

$page = new page(__FILE__, 'Update');
$page->header();
$page->navigation();

/**
 * Update
 */
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    /** Current version is below 0.2.0 */
    if (-1 === version_compare($options->version, '0.2.0')) {
        $database->query('ALTER TABLE `users`
                                  ADD `last_login` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `password`,
                                  ADD `power`      BOOLEAN  NOT NULL DEFAULT 0                 AFTER `last_login`
        ;');
        $database->query('UPDATE `users`
                             SET `power` = 100
                           WHERE `id` = ' . $user->id .
        ';');
        $database->query('ALTER TABLE `users` ADD INDEX(`password`);');

        $database->query('ALTER TABLE `wishlists`
                                  ADD `hash` VARCHAR(128) NOT NULL AFTER `name`
        ;');
        $database->query('ALTER TABLE `wishlists` ADD INDEX(`hash`);');

        $database->query('INSERT INTO `options` (`key`, `value`) VALUES ("version", "' . VERSION . '");');

        // Use this for future versions since it didn't existsin 0.1.0
        // $options->setOption('version', VERSION);
    }

    header('Location: /?page=home');
    die();
}
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php if ($user->isLoggedIn()) { ?>
            <div class="ui segment">
                <h2 class="ui header">New version detected</h2>
                <p>Thank you for updating to <strong>v<?= VERSION ?></strong>!</p>
                <p>There have been some changes in the database, please run the updater.</p>
                <div class="ui icon warning message">
                    <i class="exclamation triangle icon"></i>
                    <div class="content">
                        <div class="header">
                            Use at own risk
                        </div>
                        <p>Be sure to make backups before proceeding.</p>
                    </div>
                </div>
                <form class="ui form" method="post">
                    <button class="ui orange button" type="submit">
                        <i class="upload icon"></i>
                        Run the updater
                    </button>
                </form>
            </div>
        <?php } else { ?>
            <div class="ui segment">
                <h2 class="ui header">Maintenance</h2>
                <p>
                    The administrator of this site is currently running an update.
                    This usually just takes a couple of seconds.
                </p>
                <p>
                    Trying again in <span id="retryIn">5</span> seconds...
                </p>
                <div class="ui primary progress nolabel">
                    <div class="bar"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>

<?php
$page->footer();
?>
