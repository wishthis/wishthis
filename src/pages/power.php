<?php

/**
 * power.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Insufficient power'));
$page->header();
$page->bodyStart();
$page->navigation();

$user = User::getCurrent();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Restricted access') ?></h2>
            <p><?= sprintf(__('You do not have enough power to view this page. You need %s to see this page, but only have %s.'), '<strong>' . $_GET['required'] . '</strong>', '<strong>' . $user->getPower() . '</strong>') ?></p>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
