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
    $email    = Sanitiser::getEmail($_POST['email']);
    $password = User::generatePassword($_POST['password']);

    $database
    ->query(
        'UPDATE `users`
            SET `last_login` = NOW()
          WHERE `email`      = :user_email
            AND `password`   = :user_password;',
        array(
            'user_email'    => $email,
            'user_password' => $password,
        )
    );

    $fields = $database
    ->query(
        'SELECT *
           FROM `users`
          WHERE `email`      = :user_email
            AND `password`   = :user_password;',
        array(
            'user_email'    => $email,
            'user_password' => $password,
        )
    )
    ->fetch();

    $success = is_array($fields);

    if ($success) {
        $_SESSION['user'] = new User($fields);

        /**
         * Persisent session
         */
        if (isset($_POST['persistent'])) {
            /** Cookie options */
            $sessionLifetime = 2592000 * 4; // 4 Months
            $sessionExpires  = time() + $sessionLifetime;
            $sessionIsDev    = defined('ENV_IS_DEV') && ENV_IS_DEV || '127.0.0.1' === $_SERVER['REMOTE_ADDR'];
            $sessionOptions  = array (
                'domain'   => getCookieDomain(),
                'expires'  => $sessionExpires,
                'httponly' => true,
                'path'     => '/',
                'samesite' => 'None',
                'secure'   => !$sessionIsDev,
            );

            /** Set cookie */
            setcookie(COOKIE_PERSISTENT, session_id(), $sessionOptions);

            /** Column sessions.expires was added in v0.7.1. */
            if ($database->columnExists('sessions', 'expires')) {
                $database->query(
                    'INSERT INTO `sessions` (
                        `user`,
                        `session`,
                        `expires`
                    ) VALUES (
                        :user_id,
                        :session_id,
                        :session_expires
                    );',
                    array(
                        'user_id'         => $_SESSION['user']->id,
                        'session_id'      => session_id(),
                        'session_expires' => date('Y-m-d H:i:s', $sessionExpires),
                    )
                );
            } else {
                $database->query(
                    'INSERT INTO `sessions` (
                        `user`,
                        `session`
                    ) VALUES (
                        :user_id,
                        :session_id
                    );',
                    array(
                        'user_id'    => $_SESSION['user']->id,
                        'session_id' => session_id(),
                    )
                );
            }
        }
    } else {
        $page->messages[] = Page::error(
            __('No user could be found with the credentials you provided.'),
            __('Invalid credentials'),
        );
    }
}

if ($_SESSION['user']->isLoggedIn()) {
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
    $userQuery = $database
    ->query(
        'SELECT *
           FROM `users`
          WHERE `email` = :user_email;',
        array(
            'user_email' => Sanitiser::getEmail($_POST['email']),
        )
    );

    $user = false !== $userQuery ? new User($userQuery->fetch()) : new User();

    if (isset($user->id)) {
        $token      = sha1(time() . rand(0, 999999));
        $validUntil = time() + 3600;

        $database
        ->query(
            'UPDATE `users`
                SET `password_reset_token`       = :user_password_reset_token,
                    `password_reset_valid_until` = :user_reset_valid_until
              WHERE `id` = ' . $user->id . ';',
            array(
                'user_password_reset_token' => $token,
                'user_reset_valid_until'    => date('Y-m-d H:i:s', $validUntil),
            )
        );

        $emailReset = new Email($_POST['email'], __('Password reset link', null, $user), 'default', 'password-reset');
        $emailReset->setPlaceholder('TEXT_HELLO', __('Hello,', null, $user));
        $emailReset->setPlaceholder(
            'TEXT_PASSWORD_RESET',
            sprintf(
                /** TRANSLATORS: %s: The wishthis domain */
                __('somebody has requested a password reset for this email address from %s. If this was you, click the button below to invalidate your current password and set a new one.', null, $user),
                '<mj-raw><a href="https://wishthis.online">wishthis.online</a></mj-raw>'
            )
        );
        $emailReset->setPlaceholder('TEXT_SET_NEW_PASSWORD', __('Set new password', null, $user));
        $emailReset->setPlaceholder('wishthis.online', $_SERVER['HTTP_HOST']);
        $emailReset->setPlaceholder(
            'password-reset-link',
            $_SERVER['REQUEST_SCHEME'] . '://' .
            $_SERVER['HTTP_HOST'] .
            Page::PAGE_REGISTER . '&password-reset=' . $user->email . '&token=' . $token
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

                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="persistent">
                                    <label><?= __('Keep me logged in') ?></label>
                                </div>
                            </div>

                            <div class="inline unstackable fields">
                               <div class="field">
                                    <input class="ui primary button"
                                        type="submit"
                                        name="login"
                                        value="<?= __('Login') ?>"
                                        title="<?= __('Login') ?>"
                                    />
                               </div>

                               <div class="field">
                                    <a class="ui tertiary button"
                                    href="<?= Page::PAGE_REGISTER ?>"
                                    title="<?= __('Register') ?>"
                                    >
                                        <?= __('Register') ?>
                                    </a>
                               </div>
                            </div>
                        </form>
                    </div>

                    <div class="column">
                        <h2 class="ui header"><?= __('Forgot password?') ?></h2>

                        <p><?= __('Consider using a password manager. It will save all your passwords and allow you to access them with one master password. Never forget a password ever again.') ?></p>
                        <p><?= sprintf('%sBitwarden%s is the most trusted open source password manager.', '<a href="https://bitwarden.com/" target="_blank">', '</a>') ?></p>

                        <?php if ($options->getOption('mjml_api_application_id') && $options->getOption('mjml_api_secret_key')) { ?>
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
