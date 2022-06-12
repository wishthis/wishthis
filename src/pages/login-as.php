<?php

/**
 * Allows administrators to login as a user. For debugging purposes.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Login as'), 100);

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $user = $database->query('SELECT * FROM `users`
                               WHERE `email`    = "' . $email . '";')
                     ->fetch();

    $success = false !== $user;

    if ($success) {
        $_SESSION['user'] = $user;
    }
}

$page->header();
$page->bodyStart();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        if (isset($success)) {
            if ($success) {
                echo Page::success(sprintf(__('Successfully logged in as %s.'), $_SESSION['user']['email']), __('Success'));
            } else {
                echo Page::error(__('User not found!'), __('Error'));
            }
        }
        ?>

        <div class="ui segment">
            <form class="ui form" method="POST">
                <div class="field">
                    <label><?= __('Email') ?></label>
                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                </div>

                <input class="ui primary button"
                       type="submit"
                       value="<?= __('Login') ?>"
                       title="<?= __('Login') ?>"
                />
            </form>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
