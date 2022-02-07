<?php

/**
 * The user login page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Login');

if (isset($_POST['email'], $_POST['password'])) {
    $email    = $_POST['email'];
    $password = sha1($_POST['password']);

    $database->query('UPDATE `users`
                         SET `last_login` = NOW()
                       WHERE `email` = "' . $email . '"
                         AND `password` = "' . $password . '"
    ;');
    $user = $database->query(
        'SELECT * FROM `users`
         WHERE `email` = "' . $email . '"
         AND `password` = "' . $password . '";'
    )->fetch();

    $success = false !== $user;

    if ($success) {
        $_SESSION['user'] = $user;
    }
}

if (isset($_SESSION['user'])) {
    header('Location: /?page=home');
    die();
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        if (!$success) {
            $page->error('Invalid credentials!', 'Error');
        }
        ?>

        <div class="ui segment">
            <form class="ui form" method="post">
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" />
                </div>

                <input class="ui primary button" type="submit" value="Login" />
                <a href="/?page=register">Register</a>
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
