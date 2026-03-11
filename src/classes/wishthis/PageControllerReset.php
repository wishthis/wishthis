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
