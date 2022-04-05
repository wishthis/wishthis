<?php

/**
 * The user profile page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

if (isset($_POST['user-id'])) {
    $set = array();

    $loginRequired = false;

    if (!empty($_POST['user-email'])) {
        $set[] = '`email` = "' . $_POST['user-email'] . '"';
    }

    if (!empty($_POST['user-password']) && !empty($_POST['user-password-repeat'])) {
        $set[] = '`password` = "' . User::generatePassword($_POST['user-password']) . '"';
    }

    if ($_POST['user-birthdate']) {
        $user->birthdate = date('Y-m-d', strtotime($_POST['user-birthdate']));

        $set[] = '`birthdate` = "' . $user->birthdate . '"';
    } else {
        $user->birthdate = null;

        $set[] = '`birthdate` = NULL';
    }

    $set[] = '`locale` = "' . $_POST['user-locale'] . '"';

    $database
    ->query('UPDATE `users`
                SET ' . implode(',', $set) . '
              WHERE `id`        = ' . $_POST['user-id']);


    if ($_POST['user-email'] !== $_SESSION['user']['email']) {
        $loginRequired = true;
    }

    if ($loginRequired) {
        session_destroy();
    }

    redirect('/?page=profile');
}

$page = new Page(__FILE__, __('Profile'));
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical pointing fluid menu profile">
                    <div class="item" data-tab="personal">
                        <h4 class="ui header">Personal</h4>
                        <p>Information regarding yourself</p>
                    </div>
                    <div class="item" data-tab="password">
                        <h4 class="ui header">Password</h4>
                        <p>Change your password</p>
                    </div>
                    <div class="item" data-tab="language">
                        <h4 class="ui header">Language</h4>
                        <p>Your language preferences</p>
                    </div>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab" data-tab="personal">
                    <div class="ui segment">

                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />

                            <div class="three fields">
                                <div class="field">
                                    <label><?= __('First name') ?></label>

                                    <input type="text" name="user-name-first" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Last name') ?></label>

                                    <input type="text" name="user-name-last" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Nickname') ?></label>

                                    <input type="text" name="user-name-nick" value="" />
                                </div>
                            </div>

                            <div class="field">
                                <label><?= __('Email') ?></label>

                                <input type="email" name="user-email" value="<?= $user->email ?>" />
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Password') ?></label>

                                    <input type="password" name="user-password" autocomplete="new-password" />
                                </div>

                                <div class="field">
                                    <label><?= __('Password (repeat)') ?></label>

                                    <input type="password" name="user-password-repeat" autocomplete="new-password" />
                                </div>
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Birthdate') ?></label>

                                    <div class="ui calendar">
                                        <div class="ui input left icon">
                                            <i class="calendar icon"></i>
                                            <input type="text"
                                                name="user-birthdate"
                                                placeholder="<?= __('Pick a date') ?>"
                                                value="<?= $user->birthdate ?>"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label><?= __('Language') ?></label>

                                    <select class="ui search dropdown" name="user-locale">
                                        <?php if (!in_array('en', $locales)) { ?>
                                            <option value="<?= 'en' ?>"><?= \Locale::getDisplayName('en', $user->locale) ?></option>
                                        <?php } ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php if ($locale === $user->locale) { ?>
                                                <option value="<?= $locale ?>" selected><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $locale ?>"><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>

                    </div>
                </div>

                <div class="ui tab" data-tab="password">
                    <div class="ui segment">

                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />

                            <div class="three fields">
                                <div class="field">
                                    <label><?= __('First name') ?></label>

                                    <input type="text" name="user-name-first" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Last name') ?></label>

                                    <input type="text" name="user-name-last" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Nickname') ?></label>

                                    <input type="text" name="user-name-nick" value="" />
                                </div>
                            </div>

                            <div class="field">
                                <label><?= __('Email') ?></label>

                                <input type="email" name="user-email" value="<?= $user->email ?>" />
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Password') ?></label>

                                    <input type="password" name="user-password" autocomplete="new-password" />
                                </div>

                                <div class="field">
                                    <label><?= __('Password (repeat)') ?></label>

                                    <input type="password" name="user-password-repeat" autocomplete="new-password" />
                                </div>
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Birthdate') ?></label>

                                    <div class="ui calendar">
                                        <div class="ui input left icon">
                                            <i class="calendar icon"></i>
                                            <input type="text"
                                                name="user-birthdate"
                                                placeholder="<?= __('Pick a date') ?>"
                                                value="<?= $user->birthdate ?>"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label><?= __('Language') ?></label>

                                    <select class="ui search dropdown" name="user-locale">
                                        <?php if (!in_array('en', $locales)) { ?>
                                            <option value="<?= 'en' ?>"><?= \Locale::getDisplayName('en', $user->locale) ?></option>
                                        <?php } ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php if ($locale === $user->locale) { ?>
                                                <option value="<?= $locale ?>" selected><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $locale ?>"><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>

                    </div>
                </div>

                <div class="ui tab" data-tab="password">
                    <div class="ui segment">

                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />

                            <div class="three fields">
                                <div class="field">
                                    <label><?= __('First name') ?></label>

                                    <input type="text" name="user-name-first" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Last name') ?></label>

                                    <input type="text" name="user-name-last" value="" />
                                </div>

                                <div class="field">
                                    <label><?= __('Nickname') ?></label>

                                    <input type="text" name="user-name-nick" value="" />
                                </div>
                            </div>

                            <div class="field">
                                <label><?= __('Email') ?></label>

                                <input type="email" name="user-email" value="<?= $user->email ?>" />
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Password') ?></label>

                                    <input type="password" name="user-password" autocomplete="new-password" />
                                </div>

                                <div class="field">
                                    <label><?= __('Password (repeat)') ?></label>

                                    <input type="password" name="user-password-repeat" autocomplete="new-password" />
                                </div>
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Birthdate') ?></label>

                                    <div class="ui calendar">
                                        <div class="ui input left icon">
                                            <i class="calendar icon"></i>
                                            <input type="text"
                                                name="user-birthdate"
                                                placeholder="<?= __('Pick a date') ?>"
                                                value="<?= $user->birthdate ?>"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label><?= __('Language') ?></label>

                                    <select class="ui search dropdown" name="user-locale">
                                        <?php if (!in_array('en', $locales)) { ?>
                                            <option value="<?= 'en' ?>"><?= \Locale::getDisplayName('en', $user->locale) ?></option>
                                        <?php } ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php if ($locale === $user->locale) { ?>
                                                <option value="<?= $locale ?>" selected><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $locale ?>"><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>

                    </div>
                </div>

            </div>
        </div>

        <div class="ui segment">
            asdf
        </div>
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
