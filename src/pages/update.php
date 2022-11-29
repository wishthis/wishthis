<?php

/**
 * home.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Update'), 100);

/**
 * Update
 */
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $versions_directory = ROOT . '/src/update';
    $versions_contents  = scandir($versions_directory);
    $versions           = array();

    foreach ($versions_contents as $filename) {
        $filepath = $versions_directory . '/' . $filename;
        $pathinfo = pathinfo($filepath);

        if ('sql' === $pathinfo['extension']) {
            $versions[] = array(
                'version'  => str_replace('-', '.', $pathinfo['filename']),
                'filepath' => $filepath,
            );
        }
    }

    foreach ($versions as $version) {
        if (-1 === version_compare($options->version, $version['version'])) {
            $sql = file_get_contents($version['filepath']);

            if ($sql) {
                $database->query($sql);
            }
        }
    }

    /** Update version */
    $options->setOption('version', VERSION);
    $options->setOption('updateAvailable', false);

    /** Update service-worker.js */
    require ROOT . '/src/assets/js/service-worker.js.php';

    $page->messages[] = Page::success(
        sprintf(
            __('Database successfully migrated to %s.'),
            'v' . VERSION
        )
    );
}

$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

        <?php if (-1 === version_compare($options->version, VERSION)) { ?>
            <div class="ui segment">
                <h2 class="ui header"><?= __('Database migration') ?></h2>
                <p><?= __('Thank you for updating wishthis! To complete this update, some changes are required to the database structure.') ?></p>

                <form class="ui form" method="POST">
                    <button class="ui orange button"
                            type="submit"
                            title="<?= sprintf(__('Migrate to %s'), 'v' . VERSION) ?>"
                    >
                        <i class="upload icon"></i>
                        <?= sprintf(__('Migrate to %s'), 'v' . VERSION) ?>
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</main>

<?php
$page->bodyEnd();
?>
