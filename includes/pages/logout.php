<?php

/**
 * logout.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Logout');

session_destroy();

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <div class="ui segment">
            <h1 class="ui header"><?= $page->title ?></h1>
            <h2 class="ui header">Goodbye</h2>
            <p>You have been logged out.</p>
        </div>
    </div>
</main>

<?php
$page->footer();
