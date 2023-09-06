<?php

/**
 * The user profile page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Profile'), 1);
$user = User::getCurrent();

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
    );
    $loginRequired    = false;

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

    if (!empty($_POST['user-email']) && $_POST['user-email'] !== $user->getEmail()) {
        $loginRequired = true;
    }

    /**
     * Personal
     */
    if (isset($_POST['user-birthdate'])) {
        if (empty($_POST['user-birthdate'])) {
            $user->setBirthdate('');

            $set[] = '`birthdate` = NULL';
        } else {
            $user->setBirthdate($_POST['user-birthdate']);

            $set[] = '`birthdate` = "' . $_POST['user-birthdate'] . '"';
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
        $set[] = '`password` = "' . User::passwordToHash($_POST['user-password']) . '"';

        $loginRequired = true;
    }

    /**
     * Preferences
     */

    /** Language */
    if (isset($_POST['user-language']) && $_POST['user-language'] !== $user->getLocale()) {
        $user->setLocale($_POST['user-language']);

        $set[] = '`language` = "' . $user->getLocale() . '"';

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The new locale */
                __('Language set to %s.'),
                '<strong>' . $user->getLocale() . '</strong>'
            ),
            __('Success')
        );
    }

    /** Currency */
    if (isset($_POST['user-currency']) && $_POST['user-currency'] !== $user->getLocale() && $_POST['user-currency'] !== $user->getCurrency()) {
        $user->setCurrency($_POST['user-currency']);

        $set[] = '`currency` = "' . $user->getCurrency() . '"';

        $page->messages[] = Page::success(
            sprintf(
                /** TRANSLATORS: %s: The new locale */
                __('Currency set to %s.'),
                '<strong>' . $user->getCurrency() . '</strong>'
            ),
            __('Success')
        );
    }

    /** Channel */
    if (isset($_POST['user-channel']) && $_POST['user-channel'] !== $user->getChannel()) {
        if (empty($_POST['user-channel'])) {
            $user->setChannel('');

            $set[] = '`channel` = NULL';
        } else {
            $user->setChannel($_POST['user-channel']);

            $set[] = '`channel` = "' . $_POST['user-channel'] . '"';
        }
    }

    /** Advertisements */
    if (isset($_POST['enable-advertisements'])) {
        $user->setAdvertisements(true);

        $set[] = '`advertisements` = TRUE';
    } else {
        $user->setAdvertisements(false);

        $set[] = '`advertisements` = FALSE';
    }

    /** Save */
    if ($set) {
        $database
        ->query(
            'UPDATE `users`
                SET ' . implode(',', $set) . '
              WHERE `id` = :user_id',
            array(
                'user_id' => Sanitiser::getNumber($_POST['user-id']),
            )
        );
    }

    if ($loginRequired) {
        session_destroy();
        unset($_SESSION);

        $page->messages[] = Page::warning(
            __('It is required for you to login again.'),
            __('Success')
        );
    }

    /**
     * Account
     */
    if (isset($_POST['account-delete'])) {
        $user->delete();
        $user->logOut();

        redirect(Page::PAGE_HOME);
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
                    <div class="item" data-tab="account">
                        <div class="ui small header"><?= __('Account') ?></div>
                        <p><?= __('Configuration for your account') ?></p>
                    </div>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab" data-tab="personal">
                    <h2 class="ui header"><?= __('Personal') ?></h2>

                    <div class="ui segment">
                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
                            <input type="hidden" name="section" value="personal" />

                            <div class="three fields">
                                <div class="field">
                                    <label><?= __('First name') ?></label>

                                    <input type="text" name="user-name-first" value="<?= $user->getFirstName() ?>" />
                                </div>

                                <div class="field">
                                    <label><?= __('Last name') ?></label>

                                    <input type="text" name="user-name-last" value="<?= $user->getLastName() ?>" />
                                </div>

                                <div class="field">
                                    <label><?= __('Nickname') ?></label>

                                    <input type="text" name="user-name-nick" value="<?= $user->getNickName() ?>" />
                                </div>
                            </div>

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Email') ?></label>

                                    <input type="email" name="user-email" value="<?= $user->getEmail() ?>" />
                                </div>

                                <div class="field" data-content="<?= __('Used to suggest a wishlist called "Birthday", if it\'s coming up.') ?>">
                                    <label>
                                        <?= __('Birthdate') ?>
                                        <i class="ui small circular info icon"></i>
                                    </label>

                                    <div class="ui calendar">
                                        <div class="ui input left icon">
                                            <i class="calendar icon"></i>
                                            <input type="text"
                                                name="user-birthdate"
                                                placeholder="<?= __('Pick a date') ?>"
                                                value="<?= $user->getBirthdate() ?>"
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
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
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
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
                            <input type="hidden" name="section" value="preferences" />

                            <div class="two fields">
                                <div class="field">
                                    <label><?= __('Language') ?></label>

                                    <select class="ui search dropdown language" name="user-language">
                                        <?php if (!in_array('en_GB', $locales)) { ?>
                                            <option value="<?= 'en_GB' ?>"><?= \Locale::getDisplayName('en_GB', $user->getLocale()) ?></option>
                                        <?php } ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php if ($locale === $user->getLocale()) { ?>
                                                <option value="<?= $locale ?>" selected><?= \Locale::getDisplayName($locale, $user->getLocale()) ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $locale ?>"><?= \Locale::getDisplayName($locale, $user->getLocale()) ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="field">
                                    <label><?= __('Currency') ?></label>

                                    <select class="ui search dropdown currency" name="user-currency">
                                        <?php
                                        $currencies = array();
                                        ?>

                                        <?php foreach ($locales as $locale) { ?>
                                            <?php
                                            $currencyFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
                                            $currencyISO       = $currencyFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);
                                            $currencySymbol    = $currencyFormatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
                                            $currencyValue     = $currencyISO . ' (' . $currencySymbol . ')';

                                            if (in_array($currencyISO, $currencies, true) || $currencyISO === $currencySymbol) {
                                                continue;
                                            } else {
                                                $currencies[] = $currencyISO;
                                            }
                                            ?>

                                            <?php if ($currencyISO === $user->getCurrency()) { ?>
                                                <option value="<?= $currencyISO ?>" selected><?= $currencyValue ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $currencyISO ?>"><?= $currencyValue ?></option>
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

                    <?php
                    $user_is_active = '`last_login` >= CURDATE() - INTERVAL 60 DAY';

                    $count_users = $database
                    ->query(
                        'SELECT COUNT(`id`)
                           FROM `users`
                          WHERE ' . $user_is_active . ';'
                    )
                    ->fetch();
                    $count_users = reset($count_users);

                    $count_users_needed_minimum = 3;
                    $count_users_needed_maximum = 100;
                    $count_users_needed         = min(
                        $count_users_needed_maximum,
                        max(
                            $count_users_needed_minimum,
                            round($count_users * 0.05, 0)
                        )
                    );

                    $count_users_rc = $database
                    ->query(
                        'SELECT COUNT(`id`)
                           FROM `users`
                          WHERE ' . $user_is_active . '
                            AND `channel` = "release-candidate";'
                    )
                    ->fetch();
                    $count_users_rc = reset($count_users_rc);
                    ?>

                    <div class="ui segment">
                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
                            <input type="hidden" name="section" value="preferences" />

                            <?php if (defined('CHANNELS') && is_array(CHANNELS)) { ?>
                                <script type="text/javascript">
                                    var CHANNELS = <?= json_encode(CHANNELS) ?>;
                                </script>

                                <div class="field">
                                    <label><?= __('Channel') ?></label>

                                    <select class="ui search clearable dropdown channel" name="user-channel">
                                        <option value=""><?= __('Select channel') ?></option>

                                        <?php foreach (CHANNELS as $channel) { ?>
                                            <?php if ($channel['branch'] === $user->getChannel()) { ?>
                                                <option value="<?= $channel['branch'] ?>" selected><?= $channel['label'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $channel['branch'] ?>"><?= $channel['label'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="field">
                                    <p><?= __('In order to improve the user experience of wishthis, newer versions are published after an extensive testing period.') ?></p>
                                    <p><?= __('Subscribing to the Stable channel ensures you have the highest possible stability while using wishthis, minimizing the amount of errors you may encounter (if any).') ?></p>
                                    <p><?= __('If you want to speed up the release of newer versions, consider subscribing to the Release candidate of wishthis. A newer version is not published unless the next release candidate has been sufficiently tested.') ?></p>

                                    <?php if ($count_users_rc < $count_users_needed) { ?>
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
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="ui error message"></div>

                            <input class="ui primary button"
                                type="submit"
                                value="<?= __('Save') ?>"
                                title="<?= __('Save') ?>"
                            />
                        </form>
                    </div>

                    <div class="ui segment">
                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
                            <input type="hidden" name="section" value="preferences" />

                            <div class="field">
                                <label><?= __('Advertisements') ?></label>

                                <div class="ui toggle checkbox advertisements">
                                    <?php if (true === $user->getAdvertisements()) { ?>
                                        <input type="checkbox" name="enable-advertisements" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="enable-advertisements" />
                                    <?php } ?>

                                    <label><?= __('Enable advertisements') ?></label>
                                </div>
                            </div>

                            <div class="field">
                                <p>
                                    <?php
                                    printf(
                                        /** TRANSLATORS: %s: sponsor me */
                                        __('Time spent on wishthis is time not doing for-profit work. If you would like to support me but either can\'t or don\'t want to %s, consider selling your body to Google and becoming its product.'),
                                        '<a href="https://github.com/sponsors/grandeljay" target="_blank">' . __('sponsor me') . '</a>'
                                    );
                                    ?>
                                </p>
                                <p><?= __('Please remember to add an exception to your ad-blocker and browser.') ?></p>
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

                <div class="ui tab" data-tab="account">
                    <h2 class="ui header"><?= __('Account') ?></h2>

                    <div class="ui segment">
                        <form class="ui form" method="POST">
                            <input type="hidden" name="user-id" value="<?= $user->getId() ?>" />
                            <input type="hidden" name="section" value="account" />

                            <div class="field">
                                <label><?= __('Delete account') ?></label>

                                <div class="ui checkbox account-delete">
                                    <input type="checkbox" name="account-delete">

                                    <label><?= __('Delete this account completely and irreversibly') ?></label>
                                </div>
                            </div>

                            <div class="ui error message"></div>

                            <input class="ui negative button"
                                type="submit"
                                value="<?= __('Delete account') ?>"
                                title="<?= __('Delete account') ?>"
                            />
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
?>
