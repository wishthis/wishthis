<?php

namespace wishthis;

class PageControllerReset extends PageController
{
    protected string $id = 'reset';

    private string $resetEmail;
    private string $resetToken;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Reset password');

        if (isset($parameters['email'])) {
            $this->resetEmail = Sanitiser::getEmail($parameters['email']);
        }

        if (isset($parameters['token'])) {
            $this->resetToken = Sanitiser::getSHA1($parameters['token']);
        }

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $this->placeholders['USER_DETAILS_HEADING']  = __('Account details');
        $this->placeholders['USER_EMAIL_HEADING']    = __('Email');
        $this->placeholders['USER_EMAIL']            = $this->resetEmail;
        $this->placeholders['USER_PASSWORD_HEADING'] = __('Password');

        $this->placeholders['AUTHENTICATION_HEADING']            = __('Authentication');
        $this->placeholders['AUTHENTICATION_DESCRIPTION']        = __('Prove you are a Human, Lizard-person or Zuck-like creature. Please name a planet from our solar system.');
        $this->placeholders['AUTHENTICATION_PLANET_HEADING']     = __('Planet');
        $this->placeholders['AUTHENTICATION_PLANET_DESCRIPTION'] = __('Robots are obviously from another solar system so this will keep them at bay.');

        $this->placeholders['BUTTON_RESET_HEADING'] = __('Reset');
        $this->placeholders['BUTTON_LOGIN_HEADING'] = __('Login');
        $this->placeholders['BUTTON_LOGIN_URL']     = Page::PAGE_LOGIN;

        $this->placeholders['ABOUT_EMAIL_HEADING']       = __('About your email address');
        $this->placeholders['ABOUT_EMAIL_DESCRIPTION_1'] = __('Currently the email address is used as a unique identifier and does not have to be verified. You may enter a fake address.');
        $this->placeholders['ABOUT_EMAIL_DESCRIPTION_2'] = __('wishthis is not interested in sending you marketing emails or selling your information to third parties. Although possible to do otherwise, it is strongly recommend to enter your real email address in case you need to recover your password or receive important notifications. These do not exist yet, but some future features and options might require sending you an email (e. g. when a wish has been fulfilled).');
        $this->placeholders['ABOUT_EMAIL_DESCRIPTION_3'] = \sprintf(
            /** TRANSLATORS: %1$s: source code */
            __('Trust is a two way street and wishthis aims to be a transparent, trustworthy product, which is why the wishthis %1$s is publicly viewable.'),
            \sprintf(
                '<a href="https://github.com/wishthis/wishthis" target="_blank">%1$s</a>',
                __('source code')
            )
        );

        parent::setPlaceholders();
    }

    public function default(): void
    {
        parent::render();
    }

    public function reset(): void
    {
        global $database;

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

        $planet                = mb_strtolower($_POST['planet']);
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

        $userEmail = $this->resetEmail;
        $userToken = $this->resetToken;

        $userQuery = $database->query(
            'SELECT *
               FROM `users`
              WHERE `email`                = :user_email
                AND `password_reset_token` = :user_password_reset_token',
            [
                'user_email'                => $userEmail,
                'user_password_reset_token' => $userToken,
            ]
        );
        $userData  = $userQuery->fetch() ?: null;

        if (null === $userData) {
            $messageContent = __('This password reset link seems to have been manipulated, please request a new one.');
            $messageHeader  = __('Failure');
            $messageType    = MessageType::ERROR;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;

            parent::render();

            return;
        }

        $user = new User($userData);

        if (\time() <= $user->getPasswordResetValidUntil()) {
            $database->query(
                'UPDATE `users`
                    SET `password` = :user_password
                  WHERE `id`       = :user_id;',
                [
                    'user_password' => User::passwordToHash($_POST['password']),
                    'user_id'       => $user->getId(),
                ]
            );

            $messageContent = \sprintf(
                /** TRANSLATORS: %1$s: the user's email address */
                __('Password has been successfully reset for %1$s.'),
                \sprintf('<strong>%1$s</strong>', $userEmail)
            );
            $messageHeader = __('Success');
            $messageType   = MessageType::SUCCESS;
            $message       = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        } else {
            $messageContent = __('This password reset link has expired, please request a new one.');
            $messageHeader  = __('Success');
            $messageType    = MessageType::SUCCESS;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        parent::render();
    }
}
