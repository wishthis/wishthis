<?php

/**
 * The user profile page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new Page(__FILE__, 'Profile');
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui segment">
            <form class="ui form">
                <div class="field">
                    <label>Email</label>

                    <input type="email" name="user-email" value="<?= $user->email ?>" />
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Password</label>

                        <input type="password" name="user-password" />
                    </div>

                    <div class="field">
                        <label>Password (repeat)</label>

                        <input type="password" name="user-password-repeat" />
                    </div>
                </div>

                <div class="field">
                    <label>Birthdate</label>

                    <div class="ui calendar">
                        <div class="ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" placeholder="Pick up a date" />
                        </div>
                    </div>
                </div>

                <div class="ui error message"></div>

                <input class="ui primary button" type="submit" value="Save" />
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
