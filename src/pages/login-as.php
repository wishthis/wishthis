<?php

/**
 * Allows administrators to login as a user. For debugging purposes.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new Page(__FILE__, 'Login as', 100);

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $user = $database->query('SELECT * FROM `users`
                               WHERE `email`    = "' . $email . '";')
                     ->fetch();

    $success = false !== $user;

    if ($success) {
        $_SESSION['user'] = $user;

        echo '<pre>';
        var_dump($user);
        echo '<pre>';
    }
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        if (isset($success) && !$success) {
            echo Page::error('User not found!', 'Error');
        }
        ?>

        <div class="ui segment">
            <form class="ui form" method="post">
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                </div>

                <input class="ui primary button" type="submit" value="Login" />
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
