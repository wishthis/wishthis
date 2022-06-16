<?php

/**
 * The user login page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Login'));

/**
 * Login
 */
if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
    $email    = $_POST['email'];
    $password = User::generatePassword($_POST['password']);

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
    } else {
        $page->messages[] = Page::error(
            __('No user could be found with the credentials you provided.'),
            __('Invalid credentials'),
        );
    }
}

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['REDIRECT_URL'])) {
        redirect($_SESSION['REDIRECT_URL']);
    } else {
        redirect(Page::PAGE_HOME);
    }
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
        $token      = sha1(time() . rand(0, 999999));
        $validUntil = time() + 3600;

        $database
        ->query('UPDATE `users`
                    SET `password_reset_token`       = "' . $token . '",
                        `password_reset_valid_until` = "' . date('Y-m-d H:i:s', $validUntil) . '"
                  WHERE `id` = ' . $user['id'] . '
        ;');

        $emailReset = new Email($_POST['email'], __('Password reset link'), 'default', 'password-reset');
        $emailReset->setPlaceholder('TEXT_HELLO', __('Hello,'));
        $emailReset->setPlaceholder(
            'TEXT_PASSWORD_RESET',
            __('somebody has requested a password reset for this email address from <a href="https://wishthis.online">wishthis.online</a>. If this was you, click the button below to invalidate your current password and set a new one.')
        );
        $emailReset->setPlaceholder('TEXT_SET_NEW_PASSWORD', __('Set new password'));
        $emailReset->setPlaceholder('wishthis.online', $_SERVER['HTTP_HOST']);
        $emailReset->setPlaceholder(
            'password-reset-link',
            $_SERVER['REQUEST_SCHEME'] . '://' .
            $_SERVER['HTTP_HOST'] .
            Page::PAGE_REGISTER . '&password-reset=' . $_POST['email'] . '&token=' . $token
        );

        $emailReset->send();

        $page->messages[] = Page::info(
            __('If a match can be found for this email address, a password reset link will be sent to it.'),
            __('Info')
        );
    }
}

$page->header();
$page->bodyStart();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

        <div class="ui segment">
            <div class="ui divided relaxed stackable two column grid">

                <div class="row">
                    <div class="column">
                        <h2 class="ui header"><?= __('Credentials') ?></h2>

                        <form class="ui form login" method="POST">
                            <div class="field">
                                <label><?= __('Email') ?></label>

                                <div class="ui left icon input">
                                    <input type="email" name="email" placeholder="john.doe@domain.tld" />
                                    <i class="envelope icon"></i>
                                </div>
                            </div>

                            <div class="field">
                                <label><?= __('Password') ?></label>

                                <div class="ui left icon input">
                                    <input type="password" name="password" />
                                    <i class="key icon"></i>
                                </div>
                            </div>

                            <input class="ui primary button"
                                   type="submit"
                                   name="login"
                                   value="<?= __('Login') ?>"
                                   title="<?= __('Login') ?>"
                            />
                            <a class="ui tertiary button"
                               href="<?= Page::PAGE_REGISTER ?>"
                               title="<?= __('Register') ?>"
                            >
                                <?= __('Register') ?>
                            </a>
                        </form>
                    </div>

                    <div class="column">
                        <h2 class="ui header"><?= __('Forgot password?') ?></h2>

                        <p><?= __('Consider using a password manager. It will save all your passwords and allow you to access them with one master password. Never forget a password ever again.') ?></p>
                        <p><?= sprintf('%sBitwarden%s is the most trusted open source password manager.', '<a href="https://bitwarden.com/" target="_blank">', '</a>') ?></p>

                        <?php if ($options->getOption('mjml_api_key') && $options->getOption('mjml_api_secret')) { ?>
                            <p>
                                <form class="ui form reset" method="POST">
                                    <div class="field">
                                        <div class="ui action input">
                                            <div class="ui left icon action input">
                                                <input type="email" name="email" placeholder="john.doe@domain.tld" />
                                                <i class="envelope icon"></i>
                                            </div>

                                            <input class="ui primary button"
                                                type="submit"
                                                name="reset"
                                                value="<?= __('Send email') ?>"
                                                title="<?= __('Send email') ?>"
                                            />
                                        </div>

                                    </div>
                                </form>
                            </p>

                            <p><?= __('Please note that you have to enter the email address, you have registered with.') ?></p>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
