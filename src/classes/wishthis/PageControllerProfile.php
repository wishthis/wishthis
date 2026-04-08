<?php

namespace wishthis;

class PageControllerProfile extends PageController
{
    protected string $id = 'profile';

    public function __construct()
    {
        $this->pageTitle = __('Profile');

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $this->setPlaceholdersPersonal();
        $this->setPlaceholdersPassword();
        $this->setPlaceholdersPreferences();
        $this->setPlaceholdersAccount();

        $this->placeholders['PROFILE_SAVE'] = __('Save');

        parent::setPlaceholders();
    }

    private function setPlaceholdersPersonal(): void
    {
        $user = User::getCurrent();

        $this->placeholders['PROFILE_PERSONAL_HEADING']     = __('Personal');
        $this->placeholders['PROFILE_PERSONAL_DESCRIPTION'] = __('Information regarding yourself');

        $this->placeholders['PROFILE_PERSONAL_NAME_FIRST_HEADING']    = __('First name');
        $this->placeholders['PROFILE_PERSONAL_NAME_FIRST']            = $user->getNameFirst();
        $this->placeholders['PROFILE_PERSONAL_NAME_LAST_HEADING']     = __('Last name');
        $this->placeholders['PROFILE_PERSONAL_NAME_LAST']             = $user->getNameLast();
        $this->placeholders['PROFILE_PERSONAL_NAME_NICK_HEADING']     = __('Nickname');
        $this->placeholders['PROFILE_PERSONAL_NAME_NICK']             = $user->getNameNick();
        $this->placeholders['PROFILE_PERSONAL_EMAIL_HEADING']         = __('Email');
        $this->placeholders['PROFILE_PERSONAL_EMAIL']                 = $user->getEmail();
        $this->placeholders['PROFILE_PERSONAL_BIRTHDATE_HEADING']     = __('Birthdate');
        $this->placeholders['PROFILE_PERSONAL_BIRTHDATE_DESCRIPTION'] = __('Used to suggest a wishlist called "Birthday", if it\'s coming up.');
        $this->placeholders['PROFILE_PERSONAL_BIRTHDATE_PICK']        = __('Pick a date');
        $this->placeholders['PROFILE_PERSONAL_BIRTHDATE']             = $user->getBirthdate();
    }

    private function setPlaceholdersPassword(): void
    {
        $this->placeholders['PROFILE_PASSWORD_HEADING']             = __('Password');
        $this->placeholders['PROFILE_PASSWORD_DESCRIPTION']         = __('Change your password');
        $this->placeholders['PROFILE_PASSWORD_REPEAT']              = __('Password (repeat)');
        $this->placeholders['PROFILE_PASSWORD_CHECKLIST']           = __('Safe password checklist');
        $this->placeholders['PROFILE_PASSWORD_LONG_HEADING']        = __('Long');
        $this->placeholders['PROFILE_PASSWORD_LONG_DESCRIPTION']    = __('Over eight characters in length.');
        $this->placeholders['PROFILE_PASSWORD_SPECIAL_HEADING']     = __('Special');
        $this->placeholders['PROFILE_PASSWORD_SPECIAL_DESCRIPTION'] = __('Contains special characters.');
    }

    private function setPlaceholdersPreferences(): void
    {
        $this->placeholders['PROFILE_PREFERENCES_HEADING']          = __('Preferences');
        $this->placeholders['PROFILE_PREFERENCES_DESCRIPTION']      = __('Improve your wishthis experience');
        $this->placeholders['PROFILE_PREFERENCES_LANGUAGE_HEADING'] = __('Language');

        global $locales;

        $user = User::getCurrent();
        \ob_start();
        ?>
        <select class="ui search dropdown language" name="user-language">
            <?php if (!\in_array('en_GB', $locales)) { ?>
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
        <?php
        $this->placeholders['PROFILE_PREFERENCES_LANGUAGE_DROPDOWN'] = \ob_get_clean();

        $this->placeholders['PROFILE_PREFERENCES_CURRENCY_HEADING'] = __('Currency');

        \ob_start();
        $currencies = [];
        ?>
        <select class="ui search dropdown currency" name="user-currency">
            <?php foreach ($locales as $locale) { ?>
                <?php
                $currencyFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
                $currencyISO       = $currencyFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);
                $currencySymbol    = $currencyFormatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
                $currencyValue     = $currencyISO . ' (' . $currencySymbol . ')';

                if (\in_array($currencyISO, $currencies, true) || $currencyISO === $currencySymbol) {
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
        <?php
        $this->placeholders['PROFILE_PREFERENCES_CURRENCY_DROPDOWN'] = \ob_get_clean();
        $this->placeholders['PROFILE_PREFERENCES_SAVE']              = __('Save');

        \ob_start();
        ?>
        <?php if (\defined('CHANNELS') && \is_array(\CHANNELS)) { ?>
            <?php
            global $database;

            $queryMysql  = 'SELECT COUNT(`id`)
                            FROM `users`
                            WHERE `last_login` >= CURDATE() - INTERVAL 60 DAY';
            $querySqlite = 'SELECT COUNT("id")
                            FROM "users"
                            WHERE "last_login" >= date(\'now\', \'-60 day\')';
            $query       = match ($database->engine) {
                'mysql'  => $queryMysql,
                'sqlite' => $querySqlite,
            };

            $countUsers = $database
            ->query($query)
            ->fetch();
            $countUsers = \reset($countUsers);

            $countUsersNeededMinimum = 3;
            $countUsersNeededMaximum = 100;
            $countUsersNeeded        = \min(
                $countUsersNeededMaximum,
                \max(
                    $countUsersNeededMinimum,
                    \round($countUsers * 0.05, 0)
                )
            );

            $countUsersRc = $database
            ->query($query . \PHP_EOL . 'AND `channel` = "release-candidate"')
            ->fetch();
            $countUsersRc = \reset($countUsersRc);
            ?>

            <div class="ui segment">
                <form class="ui form" method="POST">
                    <input type="hidden" name="section" value="preferences" />

                    <script type="text/javascript">
                        var CHANNELS = <?= \json_encode(\CHANNELS) ?>;
                    </script>

                    <div class="field">
                        <label><?= __('Release Channel') ?></label>

                        <select class="ui search clearable dropdown channel" name="user-channel">
                            <option value=""><?= __('Select channel') ?></option>

                            <?php foreach (\CHANNELS as $channel) { ?>
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

                        <?php if ($countUsersRc < $countUsersNeeded) { ?>
                            <div class="ui primary progress" data-value="<?= $countUsersRc ?>" data-total="<?= $countUsersNeeded ?>">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label">
                                    <?php
                                    $countUsersNeeded = $countUsersNeeded - $countUsersRc;

                                    \printf(
                                        _n(
                                            '%d more subscriber needed',
                                            '%d more subscribers needed',
                                            $countUsersNeeded
                                        ),
                                        $countUsersNeeded
                                    )
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="ui error message"></div>

                    <input class="ui primary button" type="submit" value="<?= __('Save') ?>" title="<?= __('Save') ?>" />
                </form>
            </div>
        <?php } ?>
        <?php
        $this->placeholders['PROFILE_PREFERENCES_CHANNEL_SEGMENT'] = \ob_get_clean();
    }

    private function setPlaceholdersAccount(): void
    {
        $this->placeholders['PROFILE_ACCOUNT_HEADING']            = __('Account');
        $this->placeholders['PROFILE_ACCOUNT_DESCRIPTION']        = __('Configuration for your account');
        $this->placeholders['PROFILE_ACCOUNT_DELETE_HEADING']     = __('Delete account');
        $this->placeholders['PROFILE_ACCOUNT_DELETE_DESCRIPTION'] = __('Delete this account completely and irreversibly');
    }

    public function default(): void
    {
        parent::render();
    }

    public function update(): void
    {
        $section = $_POST['section'] ?? '';

        match ($section) {
            'personal'    => $this->updatePersonal(),
            'password'    => $this->updatePassword(),
            'preferences' => $this->updatePreferences(),
            'account'     => $this->updateAccount(),
        };

        /** `redirect` won't work, as the url target is the same. */
        \header('Location: /profile');
        die();
    }

    private function updatePersonal(): void
    {
        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        /**
         * Name (First)
         */
        if (isset($_POST['user-name-first'])) {
            $nameFirst = Sanitiser::sanitiseText($_POST['user-name-first']);

            if ($nameFirst !== $user->getNameFirst()) {
                $database->query(
                    'UPDATE `users`
                        SET `name_first` = :name_first
                    WHERE `id` = :user_id',
                    [
                        'name_first' => $nameFirst,
                        'user_id'    => $userId,
                    ]
                );

                $user->setNameFirst($nameFirst);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The users first name. */
                    __('First name updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $nameFirst)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Name (Last)
         */
        if (isset($_POST['user-name-last'])) {
            $nameLast = Sanitiser::sanitiseText($_POST['user-name-last']);

            if ($nameLast !== $user->getNameLast()) {
                $database->query(
                    'UPDATE `users`
                        SET `name_last` = :name_last
                    WHERE `id` = :user_id',
                    [
                        'name_last' => $nameLast,
                        'user_id'   => $userId,
                    ]
                );

                $user->setNameLast($nameLast);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The users first name. */
                    __('Last name updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $nameLast)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Name (Nick)
         */
        if (isset($_POST['user-name-nick'])) {
            $nameNick = Sanitiser::sanitiseText($_POST['user-name-nick']);

            if ($nameNick !== $user->getNameNick()) {
                $database->query(
                    'UPDATE `users`
                        SET `name_nick` = :name_nick
                    WHERE `id` = :user_id',
                    [
                        'name_nick' => $nameNick,
                        'user_id'   => $userId,
                    ]
                );

                $user->setNameNick($nameNick);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The users first name. */
                    __('Nickname updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $nameNick)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Email
         */
        if (isset($_POST['user-email'])) {
            $email = Sanitiser::sanitiseEmail($_POST['user-email']);

            if ($email !== $user->getEmail()) {
                $database->query(
                    'UPDATE `users`
                        SET `email` = :email
                    WHERE `id` = :user_id',
                    [
                        'email'   => $email,
                        'user_id' => $userId,
                    ]
                );

                $user->setEmail($email);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The users first name. */
                    __('Email address updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $email)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Birthdate
         */
        if (isset($_POST['user-birthdate'])) {
            $birthdateTimestamp = \strtotime($_POST['user-birthdate']);

            if (\is_int($birthdateTimestamp)) {
                $birthdate = \date('Y-m-d', $birthdateTimestamp);

                if ($birthdate !== $user->getBirthdate()) {
                    $database->query(
                        'UPDATE `users`
                            SET `birthdate` = :birthdate
                        WHERE `id` = :user_id',
                        [
                            'birthdate' => $birthdate,
                            'user_id'   => $userId,
                        ]
                    );

                    $user->setBirthdate($birthdate);

                    $messageContent = \sprintf(
                        /** TRANSLATORS: %1$s: The users first name. */
                        __('Birthdate updated to "%1$s".'),
                        \sprintf('<strong>%1$s</strong>', $birthdate)
                    );
                    $messageHeader = __('Success');
                    $messageType   = MessageType::SUCCESS;
                    $message       = new Message(
                        $messageContent,
                        $messageHeader,
                        $messageType
                    );

                    $_SESSION['messages'][] = $message;
                }
            }
        }
    }

    private function updatePassword(): void
    {
        if (
               !isset($_POST['user-password'])
            || !isset($_POST['user-password-repeat'])
            || !\strlen($_POST['user-password']) >= 8
            || !\strlen($_POST['user-password-repeat']) >= 8
            || $_POST['user-password'] !== $_POST['user-password-repeat']
        ) {
            return;
        }

        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        $password = User::passwordToHash($_POST['user-password']);

        $database->query(
            'UPDATE `users`
                SET `password` = :password
                WHERE `id` = :user_id',
            [
                'password' => $password,
                'user_id'  => $userId,
            ]
        );

        $messageContent = __('Password updated.');
        $messageHeader  = __('Success');
        $messageType    = MessageType::SUCCESS;
        $message        = new Message(
            $messageContent,
            $messageHeader,
            $messageType
        );

        $_SESSION['messages'][] = $message;
    }

    private function updatePreferences(): void
    {
        global $database;

        $user   = User::getCurrent();
        $userId = $user->getId();

        /**
         * Language
         */
        if (isset($_POST['user-language'])) {
            $userLocale = $_POST['user-language'];

            /**
             * To do
             *
             * Verify the submitted locale actually exists.
             */
            /** */

            if ($userLocale !== $user->getLocale()) {
                $database->query(
                    'UPDATE `users`
                        SET `language` = :language
                    WHERE `id` = :user_id',
                    [
                        'language' => $userLocale,
                        'user_id'  => $userId,
                    ]
                );

                $user->setLocale($userLocale);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The user's locale. */
                    __('Locale updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $userLocale)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Currency
         */
        if (isset($_POST['user-currency'])) {
            $userCurrency = $_POST['user-currency'];

            /**
             * To do
             *
             * Verify the submitted currency actually exists.
             */
            /** */

            if ($userCurrency !== $user->getCurrency()) {
                $database->query(
                    'UPDATE `users`
                        SET `currency` = :currency
                    WHERE `id` = :user_id',
                    [
                        'currency' => $userCurrency,
                        'user_id'  => $userId,
                    ]
                );

                $user->setCurrency($userCurrency);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The user's currency. */
                    __('Currency updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $userCurrency)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }

        /**
         * Channel
         */
        if (isset($_POST['user-channel'])) {
            $userChannel = $_POST['user-channel'];

            $channels = \defined('CHANNELS') ? \array_map(
                function ($channel) {
                    return $channel['branch'] ?? '';
                },
                CHANNELS
            )
            : [];

            if (\in_array($userChannel, $channels, true) && $userChannel !== $user->getChannel()) {
                $database->query(
                    'UPDATE `users`
                        SET `channel` = :channel
                    WHERE `id` = :user_id',
                    [
                        'channel' => $userChannel,
                        'user_id' => $userId,
                    ]
                );

                $user->setChannel($userChannel);

                $messageContent = \sprintf(
                    /** TRANSLATORS: %1$s: The user's channel. */
                    __('Channel updated to "%1$s".'),
                    \sprintf('<strong>%1$s</strong>', $userChannel)
                );
                $messageHeader = __('Success');
                $messageType   = MessageType::SUCCESS;
                $message       = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }

            if ('' === $userChannel && '' !== $user->getChannel()) {
                $database->query(
                    'UPDATE `users`
                        SET `channel` = :channel
                    WHERE `id` = :user_id',
                    [
                        'channel' => null,
                        'user_id' => $userId,
                    ]
                );

                $user->setChannel($userChannel);

                $messageContent = __('Channel has been reset.');
                $messageHeader  = __('Success');
                $messageType    = MessageType::SUCCESS;
                $message        = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
            }
        }
    }

    private function updateAccount(): void
    {
        $user = User::getCurrent();

        if (isset($_POST['account-delete'])) {
            $user->delete();
            $user->logOut();

            redirect(Page::PAGE_HOME);
        }
    }
}
