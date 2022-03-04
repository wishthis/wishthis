<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

$page = new Page(__FILE__, 'Update', 100);
$page->header();
$page->bodyStart();
$page->navigation();

/**
 * Update
 */
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    /**
     * Files
     */
    $zip_filename = __DIR__ . '/' . $tag . '.zip';

    /** Download */
    file_put_contents(
        $zip_filename,
        file_get_contents('https://github.com/grandeljay/wishthis/archive/refs/tags/' . $tag . '.zip')
    );

    /** Decompress */
    $zip = new ZipArchive();

    if ($zip->open($zip_filename)) {
        $zip->extractTo(__DIR__);
        $zip->close();

        $directory_wishthis_github = __DIR__ . '/wishthis-' . $version;

        foreach (scandir($directory_wishthis_github) as $filename) {
            if (in_array($filename, array('.', '..', 'config'))) {
                continue;
            }

            $filepath = __DIR__ . '/' . $filename;
            $filepath_github = $directory_wishthis_github . '/' . $filename;

            if (is_dir($filepath) && is_dir($filepath_github)) {
                delete_directory($filepath);
            }

            rename($filepath_github, $filepath);
        }
    }

    /** Delete */
    unlink($zip_filename);

    /**
     * Database
     */
    /** Current version is below 0.2.0 */
    if (-1 === version_compare($options->version, '0.2.0')) {
        $database->query('ALTER TABLE `users`
                                  ADD `last_login` DATETIME NOT NULL DEFAULT NOW() AFTER `password`,
                                  ADD `power`      BOOLEAN  NOT NULL DEFAULT 0     AFTER `last_login`
        ;');
        $database->query('UPDATE `users`
                             SET `power` = 100
                           WHERE `id` = ' . $user->id .
        ';');
        $database->query('ALTER TABLE `users` ADD INDEX(`password`);');

        $database->query('ALTER TABLE `wishlists`
                                  ADD `hash`      VARCHAR(128) NOT NULL AFTER `name`
        ;');
        $database->query('ALTER TABLE `wishlists` ADD INDEX(`hash`);');

        $database->query('INSERT INTO `options` (`key`, `value`) VALUES ("version", "' . $version . '");');
    }

    /** Current version is below 0.3.0 */
    if (-1 === version_compare($options->version, '0.3.0')) {
        $database->query('ALTER TABLE `wishes`
                                  ADD `status`   VARCHAR(32) NOT NULL AFTER `url`
        ;');
    }

    /** Update version */
    $options->setOption('version', $version);

    header('Location: /?page=home');
    die();
}
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header">New version detected</h2>
            <p>An update is available. If you are brave, please click the button to start the self updater.</p>
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
                    Update to v<?= $version ?>
                </button>
            </form>
        </div>

        <div class="ui segment">
            <h2 class="ui header">Changes</h2>

            <?= str_replace(PHP_EOL, '<br>', $release['body']) ?>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
