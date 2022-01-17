<?php

/**
 * register.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Register');

if (isset($_POST['email'], $_POST['password'])) {
    $database->query('INSERT INTO `users`
        (`email`, `password`) VALUES
        ("' . $_POST['email'] . '", "' . sha1($_POST['password']) . '")
    ;');

    header('Location: /?page=login');
    die();
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <div class="ui segment">
            <h1 class="ui header">Register</h1>

            <form class="ui form" method="post">
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" />
                </div>

                <div class="ui error message"></div>

                <input class="ui primary button" type="submit" value="Register" />
            </form>
        </div>
    </div>
</main>
<?php
$page->footer();
