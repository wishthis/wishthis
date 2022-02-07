<?php

/**
 * logout.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Logout');

if (PHP_SESSION_ACTIVE === session_status()) {
    session_destroy();

    header('Location: /?page=logout');
    die();
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header">Goodbye</h2>
            <p>You have been logged out.</p>
        </div>
    </div>
</main>

<?php
$page->footer();