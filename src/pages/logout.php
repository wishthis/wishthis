<?php

/**
 * logout.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Logout'));

if (PHP_SESSION_ACTIVE === session_status()) {
    session_destroy();

    redirect(Page::PAGE_HOME);
}

$page->header();
$page->bodyStart();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <h2 class="ui header"><?= __('Goodbye') ?></h2>
            <p><?= __('You have been logged out.') ?></p>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
