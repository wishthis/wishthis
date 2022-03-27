<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

/**
 * Update
 */
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    /**
     * Database
     */

    /** 0.5.0 *//*
    if (-1 === version_compare($options->version, '0.5.0')) {
        $database->query('ALTER TABLE `users`
                                  ADD `status`   VARCHAR(32) NOT NULL AFTER `url`
        ;');
    }*/

    /** Update version */
    $options->setOption('version', VERSION);
    $options->setOption('updateAvailable', false);

    redirect('/?page=home');
}

$page = new Page(__FILE__, __('Update'), 100);
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Database migration') ?></h2>
            <p><?= __('Thank you for updating withthis! To complete this update, some changes are required to the database structure.') ?></p>

            <form class="ui form" method="post">
                <button class="ui orange button"
                        type="submit"
                        title="<?= sprintf(__('Migrate to %s'), 'v' . VERSION) ?>"
                >
                    <i class="upload icon"></i>
                    <?= sprintf(__('Migrate to %s'), 'v' . VERSION) ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
