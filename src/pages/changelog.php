<?php

/**
 * Changelog
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Changelog'));
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui stackable grid">
            <?php
            $changelogsDirectory = ROOT . '/changelogs';
            $changelogs          = \scandir($changelogsDirectory, \SCANDIR_SORT_DESCENDING);
            $changelogs          = \array_map(
                function (string $filename) use ($changelogsDirectory) {
                    $filepath = $changelogsDirectory . '/' . $filename;

                    return $filepath;
                },
                $changelogs
            );
            $changelogs          = \array_filter($changelogs, '\is_file');
            ?>

            <div class="four wide column">
                <div class="ui vertical pointing fluid menu profile">
                    <?php
                    foreach ($changelogs as $filepath) {
                        $filenameSanitised = \pathinfo($filepath, \PATHINFO_FILENAME);
                        $firstLine         = \fgets(\fopen($filepath, 'r'));
                        $label             = \preg_replace('/[^a-zA-Z0-9\-\.]/', '', $firstLine);
                        ?>
                        <a class="item" data-tab="<?= $filenameSanitised ?>"><?= $label ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <?php
                foreach ($changelogs as $filepath) {
                    $filenameSanitised = \pathinfo($filepath, \PATHINFO_FILENAME);
                    ?>
                    <div class="ui tab" data-tab="<?= $filenameSanitised ?>">
                        <div class="ui segments">
                            <?php
                            $parsedown = new \Parsedown();
                            $text      = \file_get_contents($filepath);
                            $text      = \preg_replace('/(#(\d+))/', '<a href="https://github.com/wishthis/wishthis/issues/$2">$1</a>', $text);
                            ?>
                            <div class="ui segment"><?= $parsedown->text($text); ?></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
