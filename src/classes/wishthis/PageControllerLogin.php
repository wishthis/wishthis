<?php

namespace wishthis;

class PageControllerLogin extends PageController
{
    protected string $id = 'login';

    public function __construct()
    {
        $this->pageTitle = __('Login');

        parent::__construct();
    }

    protected function setPlaceholders(): void
    {
        $this->placeholders['LOGIN_ACTION']              = Page::PAGE_LOGIN . '/user';
        $this->placeholders['LOGIN_HEADING']             = __('Login');
        $this->placeholders['LOGIN_CREDENTIALS']         = __('Credentials');
        $this->placeholders['LOGIN_EMAIL']               = __('Email');
        $this->placeholders['LOGIN_PASSWORD']            = __('Password');
        $this->placeholders['LOGIN_KEEP']                = __('Keep me logged in');
        $this->placeholders['REGISTER_HEADING']          = __('Register');
        $this->placeholders['REGISTER_LINK']             = Page::PAGE_REGISTER;
        $this->placeholders['FORGOT_PASSWORD_HEADING']   = __('Forgot password?');
        $this->placeholders['FORGOT_PASSWORD_MANAGER']   = __('Consider using a password manager. It will save all your passwords and allow you to access them with one master password. Never forget a password ever again.');
        $this->placeholders['FORGOT_PASSWORD_BITWARDEN'] = \sprintf(
            /** TRANSLATORS: %1$s: Bitwarden */
            '%1$s is the most trusted open source password manager.',
            '<a href="https://bitwarden.com/" target="_blank">Bitwarden</a>'
        );

        global $options;

        $optionMjmlApiApplicationId = $options->getOption('mjml_api_application_id');
        $optionMjmlApiSecretKey     = $options->getOption('mjml_api_secret_key');

        \ob_start();
        ?>
        <?php if ($optionMjmlApiApplicationId && $optionMjmlApiSecretKey) { ?>
            <p>
                <form class="ui form reset" method="POST" action="<?= Page::PAGE_LOGIN . '/reset' ?>">
                    <div class="ui action input" style="display: flex;">
                        <div class="ui left icon action input" style="width: 100%;">
                            <input type="email" name="email" placeholder="john.doe@domain.tld" />
                            <i class="envelope icon"></i>
                        </div>

                        <input class="ui primary button" type="submit" name="reset" value="<?= __('Send email') ?>" title="<?= __('Send email') ?>">
                    </div>
                </form>
            </p>

            <p><?= __('Please note that you have to enter the email address, you have registered with.') ?></p>
        <?php } ?>
        <?php
        $this->placeholders['FORGOT_PASSWORD_MJML'] = \ob_get_clean();

        parent::setPlaceholders();
    }

    public function default(): void
    {
        parent::render();
    }

    public function login(): void
    {
        global $user;

        $email      = \filter_input(
            \INPUT_POST,
            'email',
            \FILTER_SANITIZE_EMAIL
        );
        $password   = $_POST['password'] ?? '';
        $persistent = isset($_POST['persistent']);

        $user->login($email, $password, $persistent);
        $user = User::getCurrent();

        if ($user->isLoggedIn()) {
            if (isset($_SESSION['REDIRECT_URL'])) {
                redirect($_SESSION['REDIRECT_URL']);
            } else {
                redirect(Page::PAGE_HOME);
            }
        } else {
            $messageContent = __('No user could be found with the credentials you provided.');
            $messageHeader  = __('Invalid credentials');
            $messageType    = MessageType::ERROR;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        parent::render();
    }

    public function reset(): void
    {
        global $database;

        $emailAddress = \filter_input(
            \INPUT_POST,
            'email',
            \FILTER_SANITIZE_EMAIL
        );

        $userQuery = $database->query(
            'SELECT *
               FROM `users`
              WHERE `email` = :user_email;',
            [
                'user_email' => $emailAddress,
            ]
        );
        $userData  = $userQuery->fetch() ?: null;

        if (null === $userData) {
            $messageContent = __('If a match can be found for this email address, a password reset link will be sent to it.');
            $messageHeader  = __('Info');
            $messageType    = MessageType::INFO;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;

            \redirect(PAGE::PAGE_LOGIN);
        }

        $user = new User($userData);

        $token           = \sha1(\time() . rand(0, 999999));
        $tokenValidUntil = \time() + 3600;

        $database->query(
            'UPDATE `users`
                SET `password_reset_token`       = :user_password_reset_token,
                    `password_reset_valid_until` = :user_reset_valid_until
              WHERE `id` = :user_id;',
            [
                'user_password_reset_token' => $token,
                'user_reset_valid_until'    => \date('Y-m-d H:i:s', $tokenValidUntil),
                'user_id'                   => $user->getId(),
            ]
        );

        $emailReset = new Email(
            $emailAddress,
            __('Password reset link', null, $user),
            'default',
            'password-reset'
        );
        $emailReset->setPlaceholder('TEXT_HELLO', __('Hello,', null, $user));
        $emailReset->setPlaceholder(
            'TEXT_PASSWORD_RESET',
            \sprintf(
                /** TRANSLATORS: %s: The wishthis domain */
                __('somebody has requested a password reset for this email address from %s. If this was you, click the button below to invalidate your current password and set a new one.', null, $user),
                '<mj-raw><a href="https://wishthis.online">wishthis.online</a></mj-raw>'
            )
        );
        $emailReset->setPlaceholder(
            'TEXT_SET_NEW_PASSWORD',
            __('Set new password', null, $user)
        );
        $emailReset->setPlaceholder('https', $_SERVER['REQUEST_SCHEME']);
        $emailReset->setPlaceholder('wishthis.online', $_SERVER['HTTP_HOST']);
        $emailReset->setPlaceholder(
            'password-reset-link',
            $_SERVER['REQUEST_SCHEME'] . '://' .
            $_SERVER['HTTP_HOST'] .
            \sprintf(
                '/reset/%1$s/%2$s',
                $emailAddress,
                $token
            )
        );

        $emailReset->send();

        $messageContent = __('If a match can be found for this email address, a password reset link will be sent to it.');
        $messageHeader  = __('Info');
        $messageType    = MessageType::INFO;
        $message        = new Message(
            $messageContent,
            $messageHeader,
            $messageType
        );

        $_SESSION['messages'][] = $message;

        \redirect(PAGE::PAGE_LOGIN);
    }
}
