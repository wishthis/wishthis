<?php

/**
 * power.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Insufficient power');
$page->header();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header">Restricted access</h2>
            <p>
                You do not have enough power to view this page.
                You need <strong><?= $_GET['required'] ?></strong> to see this page, but only have <strong><?= $user->power ?></strong>.
            </p>
        </div>
    </div>
</main>

<?php
$page->footer();
?>
