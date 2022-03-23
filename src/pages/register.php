<?php

/**
 * Register a new user
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$passwordReset = isset($_GET['password-reset'], $_GET['token']);

$pageTitle    = $passwordReset ? __('Reset password') : __('Register');
$buttonSubmit = $passwordReset ? __('Reset')          : __('Register');

$page = new Page(__FILE__, $pageTitle);

if (isset($_POST['email'], $_POST['password']) && !empty($_POST['planet'])) {
    $users  = $database->query('SELECT * FROM `users`;')->fetchAll();
    $emails = array_map(
        function ($user) {
            return $user['email'];
        },
        $users
    );

    $isHuman    = false;
    $planet     = strtolower($_POST['planet']);
    $planetName = strtoupper($planet[0]) . substr($planet, 1);
    $planets    = array(
        __('mercury'),
        __('venus'),
        __('earth'),
        __('mars'),
        __('jupiter'),
        __('saturn'),
        __('uranus'),
        __('neptune'),
    );
    $not_planets = array(
        __('pluto'),
        __('sun'),
    );

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
            /**
             * Password reset
             */
            $user = $database
            ->query('SELECT * FROM `users`
                      WHERE `email`                = "' . $_GET['password-reset'] . '"
                        AND `password_reset_token` = "' . $_GET['token'] . '";')
            ->fetch();

            if ($user) {
                if (time() > $user['password_reset_valid_until']) {
                    $database
                    ->query('UPDATE `users`
                                SET `password`                   = "' . sha1($_POST['password']) . '",
                                    `password_reset_token`       = NULL,
                                    `password_reset_valid_until` = NULL
                              WHERE `id`                         = ' . $user['id'] . ';');

                    $page->messages[] = Page::success(
                        'Password has been successfully reset for <strong>' . $_GET['password-reset'] . '</strong>.',
                        'Success'
                    );
                } else {
                    $page->messages[] = Page::error(__('This link has expired.'), __('Failure'));
                }
            } else {
                $page->messages[] = Page::error(__('This link seems invalid.'), __('Failure'));
            }
        } else {
            /**
             * Register
             */
            if (0 === count($users)) {
                $database->query('INSERT INTO `users`
                                (
                                    `email`,
                                    `password`,
                                    `power`
                                ) VALUES (
                                    "' . $_POST['email'] . '",
                                    "' . sha1($_POST['password']) . '",
                                    100
                                )
                ;');
                $userRegistered = true;
            } else {
                if (in_array($_POST['email'], $emails)) {
                    $page->messages[] = Page::error(
                        __('An account with this email address already exists.'),
                        __('Invalid email address')
                    );
                } else {
                    $database->query('INSERT INTO `users`
                                    (
                                        `email`,
                                        `password`
                                    ) VALUES (
                                        "' . $_POST['email'] . '",
                                        "' . sha1($_POST['password']) . '"
                                    )
                    ;');
                    $userRegistered = true;

                    $page->messages[] = Page::success(__('Your account was successfully created.'), __('Success'));
                }
            }
        }

        /**
         * Insert default wishlist
         */
        if ($userRegistered) {
            $userID       = $database->lastInsertID();
            $wishlistName = __('My hopes and dreams');

            $database
            ->query('INSERT INTO `wishlists`
                    (
                        `user`,
                        `name`,
                        `hash`
                    ) VALUES (
                        ' . $userID . ',
                        "' . $wishlistName . '",
                        "' . sha1(time() . $userID . $wishlistName) . '"
                    )
            ;');
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

        <div class="ui segment">
            <form class="ui form" method="post">
                <div class="ui divided relaxed stackable two column grid">

                    <div class=" row">
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
                            <p><?= __('Robots are obivously from another solar system so this will keep them at bay.') ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <div class="ui error message"></div>

                            <input class="ui primary button" type="submit" value="<?= $buttonSubmit ?>" />
                            <a class="ui tertiary button" href="/?page=login"><?= __('Login') ?></a>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</main>
<?php
$page->footer();
$page->bodyEnd();
