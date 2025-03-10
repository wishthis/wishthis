<?php

/**
 * Register a new user
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$passwordReset        = isset($_GET['password-reset'], $_GET['token']);
$registrationDisabled = defined('DISABLE_USER_REGISTRATION') && true === DISABLE_USER_REGISTRATION;

$pageTitle    = $passwordReset ? __('Reset password') : __('Register');
$buttonSubmit = $passwordReset ? __('Reset')          : __('Register');

$page = new Page(__FILE__, $pageTitle);

if (isset($_POST['email'], $_POST['password']) && !empty($_POST['planet']) && !$registrationDisabled) {
    $users      = $database
    ->query(
        'SELECT *
           FROM `users`;'
    )
    ->fetchAll();
    $emails     = array_map(
        function ($user) {
            return $user['email'];
        },
        $users
    );
    $user_email = Sanitiser::getEmail($_POST['email']);

    $isHuman     = false;
    $planet      = mb_strtolower($_POST['planet']);
    $planetName  = Sanitiser::sanitiseText(mb_strtoupper(mb_substr($planet, 0, 1)) . mb_substr($planet, 1));
    $planets     = [
        mb_strtolower(__('Mercury')),
        mb_strtolower(__('Venus')),
        mb_strtolower(__('Earth')),
        mb_strtolower(__('Mars')),
        mb_strtolower(__('Jupiter')),
        mb_strtolower(__('Saturn')),
        mb_strtolower(__('Uranus')),
        mb_strtolower(__('Neptune')),
    ];
    $not_planets = [
        mb_strtolower(__('Pluto')),
        mb_strtolower(__('Sun')),
    ];

    if (in_array($planet, array_merge($planets, $not_planets))) {
        $isHuman = true;
    }

    if (in_array($planet, $not_planets)) {
        $page->messages[] = Page::warning(
            sprintf(__('%s is not a planet but I\'ll let it slide, since only a human would make this kind of mistake.'), '<strong>' . $planetName . '</strong>'),
            __('Invalid planet')
        );
    }

    if ($isHuman) {
        $userRegistered = false;

        if (isset($_GET['password-reset'], $_GET['token'])) {
            $user_email = Sanitiser::getEmail($_GET['password-reset']);
            $user_token = Sanitiser::getSHA1($_GET['token']);

            /**
             * Password reset
             */
            $userQuery = $database
            ->query(
                'SELECT *
                   FROM `users`
                  WHERE `email`                = :user_email
                    AND `password_reset_token` = :user_password_reset_token',
                [
                    'user_email'                => $user_email,
                    'user_password_reset_token' => $user_token,
                ]
            );

            if (false !== $userQuery) {
                $user = new User($userQuery->fetch());

                echo \date('d.m.Y H:i') . ' <= ' . \date('d.m.Y H:i', $user->getPasswordResetValidUntil()) . '.';
                if (time() <= $user->getPasswordResetValidUntil()) {
                    $database
                    ->query(
                        'UPDATE `users`
                            SET `password` = :user_password
                          WHERE `id`       = :user_id;',
                        [
                            'user_password' => User::passwordToHash($_POST['password']),
                            'user_id'       => $user->getId(),
                        ]
                    );

                    $page->messages[] = Page::success(
                        'Password has been successfully reset for <strong>' . $user_email . '</strong>.',
                        'Success'
                    );
                } else {
                    $page->messages[] = Page::error(__('This password reset link has expired, please request a new one.'), __('Failure'));
                }
            } else {
                $page->messages[] = Page::error(__('This password reset link seems to have been manipulated, please request a new one.'), __('Failure'));
            }
        } else {
            $locale_browser = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : DEFAULT_LOCALE;
            $locale_user    = DEFAULT_LOCALE;

            if (in_array($locale_browser, $locales, true)) {
                $locale_user = $locale_browser;
            }

            /**
             * Register
             */

            if (0 === count($users)) {
                $database->query(
                    'INSERT INTO `users` (
                        `email`,
                        `password`,
                        `power`,
                        `language`
                    ) VALUES (
                        :user_email,
                        :user_password,
                        100,
                        :user_language
                    );',
                    [
                        'user_email'    => $user_email,
                        'user_password' => User::passwordToHash($_POST['password']),
                        'user_language' => $locale_user,
                    ]
                );
                $userRegistered = true;
            } else {
                if (in_array($user_email, $emails)) {
                    $page->messages[] = Page::error(
                        __('An account with this email address already exists.'),
                        __('Invalid email address')
                    );
                } else {
                    $database->query(
                        'INSERT INTO `users` (
                            `email`,
                            `password`,
                            `language`
                        ) VALUES (
                            :user_email,
                            :user_password,
                            :user_language
                        );',
                        [
                            'user_email'    => $user_email,
                            'user_password' => User::passwordToHash($_POST['password']),
                            'user_language' => $locale_user,
                        ]
                    );
                    $userRegistered = true;

                    $page->messages[] = Page::success(__('Your account was successfully created.'), __('Success'));
                }
            }
        }

        /**
         * Insert default wishlist
         */
        if ($userRegistered) {
            $user_id       = $database->lastInsertID();
            $wishlist_name = addslashes(filter_var(__('My hopes and dreams'), FILTER_SANITIZE_SPECIAL_CHARS));
            $wishlist_hash = sha1(time() . $user_id . $wishlist_name);

            $database
            ->query(
                'INSERT INTO `wishlists` (
                    `user`,
                    `name`,
                    `hash`
                ) VALUES (
                    :wishlist_user_id,
                    :wishlist_name,
                    :wishlist_hash
                );',
                [
                    'wishlist_user_id' => $user_id,
                    'wishlist_name'    => $wishlist_name,
                    'wishlist_hash'    => $wishlist_hash,
                ]
            );
        }
    } else {
        $page->messages[] = Page::error(
            sprintf(__('%s is not a planet in our solar system. Read this for more information: %s.'), '<strong>' . $planetName . '</strong>', '<a href="https://www.space.com/16080-solar-system-planets.html" target="_blank">Solar system planets: Order of the 8 (or 9) planets</a>'),
            __('Invalid planet')
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

        <?php if ($registrationDisabled) { ?>
            <div class="ui segment">
                <p>The owner of this instance has disabled user registration.</p>
            </div>
        <?php } else { ?>
            <div class="ui segment">
                <form class="ui form" method="POST">
                    <div class="ui divided relaxed stackable two column grid">

                        <div class="row">
                            <div class="column">
                                <h2 class="ui header"><?= __('Account details') ?></h2>

                                <div class="field">
                                    <label><?= __('Email') ?></label>

                                    <div class="ui left icon input<?= isset($_GET['password-reset']) ? ' disabled' : '' ?>">
                                        <?php if (isset($_GET['password-reset'])) { ?>
                                            <input type="email"
                                                name="email"
                                                placeholder="john.doe@domain.tld"
                                                value="<?= $_GET['password-reset'] ?>"
                                                readonly
                                            />
                                        <?php } else { ?>
                                            <input type="email" name="email" placeholder="john.doe@domain.tld" />
                                        <?php } ?>
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
                            </div>

                            <div class="column">
                                <h2 class="ui header"><?= __('Authentication') ?></h2>
                                <p><?= __('Prove you are a Human, Lizard-person or Zuck-like creature. Please name a planet from our solar system.') ?></p>

                                <div class="field">
                                    <label><?= __('Planet') ?></label>

                                    <div class="ui left icon input">
                                        <input type="text" name="planet" />
                                        <i class="globe icon"></i>
                                    </div>
                                </div>
                                <p><?= __('Robots are obviously from another solar system so this will keep them at bay.') ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="sixteen wide column">
                                <div class="ui error message"></div>

                                <input class="ui primary button"
                                    type="submit"
                                    value="<?= $buttonSubmit ?>"
                                    title="<?= $buttonSubmit ?>"
                                />
                                <a class="ui tertiary button"
                                href="<?= Page::PAGE_LOGIN ?>"
                                title="<?= __('Login') ?>"
                                >
                                    <?= __('Login') ?>
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="ui segment">
                <h2 class="ui header"><?= __('About your email address') ?></h2>

                <p><?= __('Currently the email address is used as a unique identifier and does not have to be verified. You may enter a fake address.') ?></p>
                <p><?= __('wishthis is not interested in sending you marketing emails or selling your information to third parties. Although possible to do otherwise, it is strongly recommend to enter your real email address in case you need to recover your password or receive important notifications. These do not exist yet, but some future features and options might require sending you an email (e. g. when a wish has been fulfilled).') ?></p>
                <p>
                    <?=
                    sprintf(
                        /** TRANSLATORS: %s: source code */
                        __('Trust is a two way street and wishthis aims to be a transparent, trustworthy product, which is why the wishthis %s is publicly viewable.'),
                        '<a href="https://github.com/wishthis/wishthis" target="_blank">' . __('source code') . '</a>'
                    )
                    ?>
                </p>
            </div>
        <?php } ?>

    </div>
</main>
<?php
$page->bodyEnd();
