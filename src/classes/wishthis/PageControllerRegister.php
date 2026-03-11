<?php

namespace wishthis;

class PageControllerRegister extends PageController
{
    protected string $id = 'register';

    private string $email;
    private string $password;
    private string $planet;

    public function __construct()
    {
        $this->pageTitle = __('Register');

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $registrationDisabled = \defined('DISABLE_USER_REGISTRATION') && true === \DISABLE_USER_REGISTRATION;

        \ob_start();
        ?>
        <?php if ($registrationDisabled) { ?>
            <div class="ui segment">
                <p><?= __('The owner of this instance has disabled user registration.') ?></p>
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

                                <input class="ui primary button" type="submit" value="<?= __('Register') ?>" title="<?= __('Register') ?>" />
                                <a class="ui tertiary button" href="<?= Page::PAGE_LOGIN ?>" title="<?= __('Login') ?>">
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
                    \sprintf(
                        /** TRANSLATORS: %s: source code */
                        __('Trust is a two way street and wishthis aims to be a transparent, trustworthy product, which is why the wishthis %s is publicly viewable.'),
                        '<a href="https://github.com/wishthis/wishthis" target="_blank">' . __('source code') . '</a>'
                    )
                    ?>
                </p>
            </div>
        <?php } ?>

        <?php
        $this->placeholders['REGISTER_FORM'] = \ob_get_clean();

        parent::setPlaceholders();
    }

    public function default(): void
    {
        parent::render();
    }

    public function register(): void
    {
        global $database;

        $this->email    = \filter_input(\INPUT_POST, 'email', \FILTER_SANITIZE_EMAIL);
        $this->password = User::passwordToHash($_POST['password']);
        $this->planet   = \htmlspecialchars($_POST['planet']);

        $users = $database->query(
            'SELECT *
                FROM `users`;'
        )
        ->fetchAll();

        $emails = \array_map(
            function (array $user): string {
                return $user['email'];
            },
            $users
        );

        $planet                = \mb_strtolower($this->planet);
        $planetNameCapitalised = \mb_strtoupper(\mb_substr($planet, 0, 1))
                               . \mb_substr($planet, 1);
        $planetName            = Sanitiser::sanitiseText($planetNameCapitalised);
        $planets               = [
            \mb_strtolower(__('Mercury')),
            \mb_strtolower(__('Venus')),
            \mb_strtolower(__('Earth')),
            \mb_strtolower(__('Mars')),
            \mb_strtolower(__('Jupiter')),
            \mb_strtolower(__('Saturn')),
            \mb_strtolower(__('Uranus')),
            \mb_strtolower(__('Neptune')),
        ];
        $notPlanets            = [
            \mb_strtolower(__('Pluto')),
            \mb_strtolower(__('Sun')),
        ];
        $isHuman               = \in_array($planet, \array_merge($planets, $notPlanets));

        if (\in_array($planet, $notPlanets)) {
            $messageContent = \sprintf(
                /** TRANSLATORS: %1$s: name of the planet */
                __('%1$s is not a planet but I\'ll let it slide, since only a human would make this kind of mistake.'),
                \sprintf('<strong>%1$s</strong>', $planetName)
            );
            $messageHeader = __('Invalid planet');
            $messageType   = MessageType::WARNING;
            $message       = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        if (!$isHuman) {
            $messageContent = \sprintf(
                /** TRANSLATORS: %1$s: name of the planet, %2$s: link to space.com */
                __('%1$s is not a planet in our solar system. Read this for more information: %2$s.'),
                \sprintf('<strong>%1$s</strong>', $planetName),
                '<a href="https://www.space.com/16080-solar-system-planets.html" target="_blank">Solar system planets: Order of the 8 (or 9) planets</a>'
            );
            $messageHeader = __('Invalid planet');
            $messageType   = MessageType::ERROR;
            $message       = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;

            parent::render();

            return;
        }

        global $locales;

        $localeBrowser = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                       ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                       : \DEFAULT_LOCALE;
        $localeUser    = \DEFAULT_LOCALE;

        $userRegistered = false;
        $userEmail      = $this->email;
        $userPassword   = $this->password;

        if (\in_array($localeBrowser, $locales, true)) {
            $localeUser = $localeBrowser;
        }

        if (0 === \count($users)) {
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
                    'user_email'    => $userEmail,
                    'user_password' => $userPassword,
                    'user_language' => $localeUser,
                ]
            );
            $userRegistered = true;
        } else {
            if (\in_array($userEmail, $emails)) {
                $messageContent = __('An account with this email address already exists.');
                $messageHeader  = __('Invalid email address');
                $messageType    = MessageType::ERROR;
                $message        = new Message(
                    $messageContent,
                    $messageHeader,
                    $messageType
                );

                $_SESSION['messages'][] = $message;
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
                        'user_email'    => $userEmail,
                        'user_password' => $userPassword,
                        'user_language' => $localeUser,
                    ]
                );
                $userRegistered = true;

                $messageContent = __('Your account was successfully created.');
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

        /**
         * Insert default wishlist
         */
        if ($userRegistered) {
            $user_id       = $database->lastInsertID();
            $wishlist_name = \addslashes(
                \filter_var(
                    __('My hopes and dreams'),
                    \FILTER_SANITIZE_SPECIAL_CHARS
                )
            );
            $wishlist_hash = \sha1(\time() . $user_id . $wishlist_name);

            $database->query(
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

        parent::render();
    }
}
