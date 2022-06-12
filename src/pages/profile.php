<?php

/**
 * The user profile page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

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

    /**
     * Personal
     */
    if (isset($_POST['user-birthdate'])) {
        if (empty($_POST['user-birthdate'])) {
            $user->birthdate = null;

            $set[] = '`birthdate` = NULL';
        } else {
            $user->birthdate = date('Y-m-d', strtotime($_POST['user-birthdate']));

            $set[] = '`birthdate` = "' . $user->birthdate . '"';
        }
    }

    /**
     * Password
     */
    if (
           !empty($_POST['user-password'])
        && !empty($_POST['user-password-repeat'])
        && $_POST['user-password'] === $_POST['user-password-repeat']
    ) {
        $set[] = '`password` = "' . User::generatePassword($_POST['user-password']) . '"';

        $loginRequired = true;
    }

    /**
     * Preferences
     */
    if (isset($_POST['user-channel']) && $_POST['user-channel'] !== $user->channel) {
        if (empty($_POST['user-channel'])) {
            $user->channel = null;

            $set[] = '`channel` = NULL';
        } else {
            $user->channel = $_POST['user-channel'];

            $set[] = '`channel` = "' . $user->channel . '"';
        }
    }

    if ($set) {
        $database
        ->query('UPDATE `users`
                    SET ' . implode(',', $set) . '
                  WHERE `id` = ' . $_POST['user-id']);
    }

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
                        <div class="ui small header"><?= __('Personal') ?></div>
                        <p><?= __('Information regarding yourself') ?></p>
                    </div>
                    <div class="item" data-tab="password">
                        <div class="ui small header"><?= __('Password') ?></div>
                        <p><?= __('Change your password') ?></p>
                    </div>
                    <div class="item" data-tab="preferences">
                        <div class="ui small header"><?= __('Preferences') ?></div>
                        <p><?= __('Improve your wishthis experience') ?></p>
                    </div>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab" data-tab="personal">
                    <h2 class="ui header"><?= __('Personal') ?></h2>

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
                    <h2 class="ui header"><?= __('Password') ?></h2>

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

                    <h3 class="ui header"><?= __('Safe password checklist') ?></h3>

                    <div class="ui basic fitted segment">
                        <div class="ui three steps">
                            <div class="disabled step long">
                                <i class="times icon"></i>
                                <div class="content">
                                    <div class="title"><?= __('Long') ?></div>
                                    <div class="description"><?= __('Over eight characters in length.') ?></div>
                                </div>
                            </div>

                            <div class="disabled step special">

                                <i class="times icon"></i>
                                <div class="content">
                                    <div class="title">
                                        <?php
                                        /** TRANSLATORS: A special character (for a password) */
                                        __('Special');
                                        ?>
                                    </div>
                                    <div class="description"><?= __('Contains special characters.') ?></div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="ui tab" data-tab="preferences">
                    <h2 class="ui header"><?= __('Preferences') ?></h2>

                    <div class="ui segment">
                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->id ?>" />
                            <input type="hidden" name="section" value="preferences" />

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Language') ?></label>

                                    <select class="ui search dropdown locale" name="user-locale">
                                        <?php if (!in_array('en_GB', $locales)) { ?>
                                            <option value="<?= 'en_GB' ?>"><?= \Locale::getDisplayName('en_GB', $user->locale) ?></option>
                                        <?php } ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php if (\Locale::getRegion($locale)) { ?>
                                                <?php if ($locale === $user->locale) { ?>
                                                    <option value="<?= $locale ?>" selected><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $locale ?>"><?= \Locale::getDisplayName($locale, $user->locale) ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <?php if (defined('CHANNELS') && is_array(CHANNELS)) { ?>
                                    <script type="text/javascript">
                                        var CHANNELS = <?= json_encode(CHANNELS) ?>;
                                    </script>

                                    <div class="field">
                                        <label><?= __('Channel') ?></label>

                                        <select class="ui search clearable dropdown channel" name="user-channel">
                                            <option value=""><?= __('Select channel') ?></option>

                                            <?php foreach (CHANNELS as $channel) { ?>
                                                <?php if ($channel['branch'] === $user->channel) { ?>
                                                    <option value="<?= $channel['branch'] ?>" selected><?= $channel['label'] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $channel['branch'] ?>"><?= $channel['label'] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>

                    </div>

                    <?php
                    $user_is_active = '`last_login` >= CURDATE() - INTERVAL 30 DAY';

                    $count_users = $database
                    ->query('SELECT COUNT(`id`)
                               FROM `users`
                              WHERE ' . $user_is_active . ';')
                    ->fetch();
                    $count_users = reset($count_users);

                    $count_users_needed_minimum = 1;
                    $count_users_needed_maximum = 100;
                    $count_users_needed         = min(
                        $count_users_needed_maximum,
                        max(
                            $count_users_needed_minimum,
                            round($count_users * 0.05, 0)
                        )
                    );

                    $count_users_rc = $database
                    ->query('SELECT COUNT(`id`)
                               FROM `users`
                              WHERE ' . $user_is_active . '
                                AND `channel` = "release-candidate";')
                    ->fetch();
                    $count_users_rc = reset($count_users_rc);
                    ?>

                    <?php if ($count_users_rc < $count_users_needed) { ?>
                        <h3 class="ui header"><?= __('Channel') ?></h3>

                        <div class="ui segment">
                            <p><?= __('In order to improve the user experience of wishthis, newer versions are published after an extensive testing period.') ?></p>
                            <p><?= __('Subscribing to the Stable channel ensures you have the highest possible stability while using wishthis, minimizing the amount of errors you may encounter (if any).') ?></p>
                            <p><?= __('If you want to speed up the release of newer versions, consider subscribing to the Release candidate of wishthis. A newer version is not published unless the next release candidate has been sufficiently tested.') ?></p>

                            <div class="ui primary progress" data-value="<?= $count_users_rc ?>" data-total="<?= $count_users_needed ?>">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label">
                                    <?php
                                    $count_users_needed = $count_users_needed - $count_users_rc;

                                    printf(
                                        _n(
                                            '%d more subscriber needed',
                                            '%d more subscribers needed',
                                            $count_users_needed
                                        ),
                                        $count_users_needed
                                    )
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
