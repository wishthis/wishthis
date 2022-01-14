<?php

/**
 * login.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Login');

if (isset($_POST['email'], $_POST['password'])) {
    $user = $database->query(
        'SELECT * FROM `users`
         WHERE `email` = "' . $_POST['email'] . '"
         AND `password` = "' . sha1($_POST['password']) . '";'
    )->fetch();

    $_SESSION['user'] = $user;
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
        <div class="ui segment">
            <h1 class="ui header">Login</h1>

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
            </form>
        </div>
    </div>
</main>

<?php
$page->footer();
