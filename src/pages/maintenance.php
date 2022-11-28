<?php

/**
 * maintenance.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

if ($options && $options->getOption('version') === VERSION) {
    redirect(Page::PAGE_HOME);
}

$page = new Page(__FILE__, __('Maintenance'));
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Temporarily unavailable') ?></h2>
            <p><?= __('Due to maintenance, wishthis is temporarily not available. Please check back again in a minute.') ?></p>
            <p><?= __('If you are the administrator of this site, please log in.') ?></p>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
