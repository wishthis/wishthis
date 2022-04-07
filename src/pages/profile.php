<?php

/**
 * The user profile page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User};

$page = new Page(__FILE__, __('Profile'));

if (isset($_POST['user-id'], $_POST['section'])) {
    $set              = array();
    $formFieldsString = array(
        array(
            'column' => 'name_first',
            'key'    => 'user-name-first',
            'label'  => __('First name'),
        ),
        array(
            'column' => 'name_last',
            'key'    => 'user-name-last',
            'label'  => __('Last name'),
        ),
        array(
            'column' => 'name_nick',
            'key'    => 'user-name-nick',
            'label'  => __('Nickname'),
        ),
        array(
            'column' => 'email',
            'key'    => 'user-email',
            'label'  => __('Email'),
        ),
        array(
            'column' => 'locale',
            'key'    => 'user-locale',
            'label'  => __('Language'),
        ),
    );
    $loginRequired   = false;

    foreach ($formFieldsString as $field) {
        if (!empty($_POST[$field['key']]) && $_POST[$field['key']] !== $user->{$field['column']}) {
            $set[] = '`' . $field['column'] . '` = "' . $_POST[$field['key']] . '"';

            $user->{$field['column']} = $_POST[$field['key']];

            $page->messages[] = Page::success(
                sprintf(
                    __('%s successfully updated!'),
                    '<strong>' . $field['label'] . '</strong>'
                ),
                __('Success')
            );
        }
    }

    if (!empty($_POST['user-email']) && $_POST['user-email'] !== $user->email) {
        $loginRequired = true;
    }

    if (isset($_POST['user-birthdate'])) {
        if (empty($_POST['user-birthdate'])) {
            $user->birthdate = null;

            $set[] = '`birthdate` = NULL';
        } else {
            $user->birthdate = date('Y-m-d', strtotime($_POST['user-birthdate']));

            $set[] = '`birthdate` = "' . $user->birthdate . '"';
        }
    }

    if (
           !empty($_POST['user-password'])
        && !empty($_POST['user-password-repeat'])
        && $_POST['user-password'] === $_POST['user-password-repeat']
    ) {
        $set[] = '`password` = "' . User::generatePassword($_POST['user-password']) . '"';

        $loginRequired = true;
    }

    $database
    ->query('UPDATE `users`
                SET ' . implode(',', $set) . '
              WHERE `id`        = ' . $_POST['user-id']);

    if ($loginRequired) {
        session_destroy();

        $page->messages[] = Page::warning(
            __('It is required for you to login again.'),
            __('Success')
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

        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical pointing fluid menu profile">
                    <div class="item" data-tab="personal">
                        <h4 class="ui header"><?= __('Personal') ?></h4>
                        <p><?= __('Information regarding yourself') ?></p>
                    </div>
                    <div class="item" data-tab="password">
                        <h4 class="ui header"><?= __('Password') ?></h4>
                        <p><?= __('Change your password') ?></p>
                    </div>
                    <div class="item" data-tab="preferences">
                        <h4 class="ui header"><?= __('Preferences') ?></h4>
                        <p><?= __('Improve your withthis experience') ?></p>
                    </div>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab" data-tab="personal">
                    <div class="ui segment">

                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />
                            <input type="hidden" name="section" value="personal" />

                            <div class="three fields">
                                <div class="field">
                                    <label><?= __('First name') ?></label>

                                    <input type="text" name="user-name-first" value="<?= $user->name_first ?>" />
                                </div>

                                <div class="field">
                                    <label><?= __('Last name') ?></label>

                                    <input type="text" name="user-name-last" value="<?= $user->name_last ?>" />
                                </div>

                                <div class="field">
                                    <label><?= __('Nickname') ?></label>

                                    <input type="text" name="user-name-nick" value="<?= $user->name_nick ?>" />
                                </div>
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Email') ?></label>

                                    <input type="email" name="user-email" value="<?= $user->email ?>" />
                                </div>

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
                            <input type="hidden" name="section" value="password" />

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

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>

                    </div>
                </div>

                <div class="ui tab" data-tab="preferences">
                    <div class="ui segment">

                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />
                            <input type="hidden" name="section" value="preferences" />

                            <div class="two fields">
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
    </div>
</main>

<?php
$page->footer();
$page->bodyEnd();
?>
