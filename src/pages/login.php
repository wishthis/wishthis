<?php

/**
 * The user login page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, Email};

$page = new Page(__FILE__, 'Login');

/**
 * Login
 */
if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
    $email    = $_POST['email'];
    $password = sha1($_POST['password']);

    $database->query('UPDATE `users`
                         SET `last_login` = NOW()
                       WHERE `email` = "' . $email . '"
                         AND `password` = "' . $password . '"
    ;');
    $user = $database->query('SELECT * FROM `users`
                               WHERE `email`    = "' . $email . '"
                                 AND `password` = "' . $password . '";')
                     ->fetch();

    $success = false !== $user;

    if ($success) {
        $_SESSION['user'] = $user;
    }
}

if (isset($_SESSION['user'])) {
    header('Location: /?page=home');
    die();
}

/**
 * Reset
 */
if (isset($_POST['reset'], $_POST['email'])) {
    $user = $database
    ->query('SELECT *
               FROM `users`
              WHERE `email` = "' . $_POST['email'] . '";')
    ->fetch();

    if ($user) {
        $mjml = file_get_contents(ROOT . '/src/mjml/password-reset.mjml');
        $mjml = str_replace('https://wishthis.online', $_SERVER['HTTP_HOST'], $mjml);

        $emailReset = new Email($_POST['email'], 'Password reset link', $mjml);
        $emailReset->send();
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
        if (isset($success) && !$success) {
            echo Page::error('Invalid credentials!', 'Error');
        }
        ?>

        <div class="ui segment">
            <div class="ui divided relaxed stackable two column grid">

                <div class="row">
                    <div class="column">
                        <h2 class="ui header">Credentials</h2>

                        <form class="ui form login" method="post">
                            <div class="field">
                                <label>Email</label>

                                <div class="ui left icon input">
                                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                                    <i class="envelope icon"></i>
                                </div>
                            </div>

                            <div class="field">
                                <label>Password</label>

                                <div class="ui left icon input">
                                    <input type="password" name="password" />
                                    <i class="key icon"></i>
                                </div>
                            </div>

                            <input class="ui primary button" type="submit" name="login" value="Login" />
                            <a class="ui tertiary button" href="/?page=register">Register</a>
                        </form>
                    </div>

                    <div class="column">
                        <h2 class="ui header">Forgot password?</h2>

                        <p>
                            Consider using a password manager.
                            It will save all your passwords and allow you to access them with one master password.
                            Never forget a password ever again.
                        </p>
                        <p><a href="https://bitwarden.com/" target="_blank">Bitwarden</a> is the most trusted open source password manager.</p>

                        <p>
                            <form class="ui form reset" method="post">
                                <div class="field">
                                    <div class="ui action input">
                                        <div class="ui left icon action input">
                                            <input type="email" name="email" placeholder="john.doe@domain.tld" />
                                            <i class="envelope icon"></i>
                                        </div>

                                        <input class="ui primary button" type="submit" name="reset" value="Send email" />
                                    </div>

                                </div>
                            </form>
                        </p>

                        <p>Please note that you have to enter the email address, you have registered with.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
