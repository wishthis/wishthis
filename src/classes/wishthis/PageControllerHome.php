<?php

namespace wishthis;

class PageControllerHome extends PageController
{
    protected string $id = 'home';

    public function __construct()
    {
        $this->template  = \sprintf('%1$s.html', $this->id);
        $this->pageTitle = __('Home');

        $this->placeholders['APP_HEADING']              = __('Make a wish');
        $this->placeholders['APP_DESCRIPTION']          = __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.');
        $this->placeholders['USE_CASE_HEADING']         = __('Use case');
        $this->placeholders['USE_CASE_DESCRIPTION']     = __('Your birthday is coming up and you just created a wishlist with all the cool stuff you want. Your friends and family want to make sure you get something you are happy with so you send them your wishlist link and if anybody decides to fulfil one of your wishes, it will disappear for everybody else.');
        $this->placeholders['WHY_WISHTHIS_HEADING']     = __('Why wishthis?');
        $this->placeholders['WHY_WISHTHIS_DESCRIPTION'] = \sprintf(
            /** TRANSLATORS: %1$s: view and verify it's code */
            __('wishthis is free and open source software. With free I don\'t just mean, you don\'t have to pay money to use it, but you are also not paying with your personal information and behaviour. Not only can anybody %1$s, I also encourage you to do so.'),
            \sprintf(
                '<a href="https://github.com/wishthis/wishthis" title="%1$s" target="_blank">%2$s</a>',
                __('wishthis source code'),
                __('view and verify it\'s code')
            )
        );
        $this->placeholders['AS_OSS_HEADING']                = __('As an open source project it remains');
        $this->placeholders['AS_OSS_FREE_OF_ADS']            = __('free of advertisements');
        $this->placeholders['AS_OSS_WITHOUT_TRACKING_POPUP'] = \sprintf(
            /** TRANSLATORS: %1$s: plausible */
            __('see %1$s'),
            '<a href=\'https://plausible.io\' target=\'_blank\'>' . __('plausible') . ' <i class=\'external alternate icon\'></i></a>'
        );
        $this->placeholders['AS_OSS_WITHOUT_TRACKING']  = __('without intrusive tracking');
        $this->placeholders['AS_OSS_TRANSPARENT']       = __('transparent');
        $this->placeholders['AS_OSS_PRIVACY_FOCUSED']   = __('transparent');
        $this->placeholders['AS_OSS_OPEN_FOR_FEEDBACK'] = __('open for feedback and suggestions');
        $this->placeholders['NEWS_HEADING']             = __('News');
        $this->placeholders['STATISTICS_HEADING']       = __('Statistics');
        $this->placeholders['STATISTICS_JOIN']          = __('Join the others and get started now!');
        $this->placeholders['STATISTICS_N_A']           = __('N. A.');
        $this->placeholders['STATISTICS_WISHES']        = __('Wishes');
        $this->placeholders['STATISTICS_WISHLISTS']     = __('Wishlists');
        $this->placeholders['STATISTICS_USERS']         = __('Registered users');

        global $user, $locales;

        $localebrowser = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                       ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                       : DEFAULT_LOCALE;
        $localeuser    = $user->getLocale();

        \ob_start();

        if (
            $user->isLoggedIn() && $localebrowser !== $localeuser &&
            \in_array($localebrowser, $locales, true)
        ) {
            ?>
            <div class="ui segment">
                <h2 class="ui header"><?= __('Hey, you') ?></h2>

                <p>
                    <?php
                    \printf(
                        /** TRANSLATORS: %s: the user's display name */
                        __('Yes, I mean you, %s.'),
                        $user->getDisplayName()
                    );
                    ?>
                </p>

                <p>
                    <?php
                    \printf(
                        /** TRANSLATORS: %1$s: Locale, e. g. German (Germany), %2$s: Locale, e. g. English (United Kingdom) %3$s: preferences */
                        __('Your browser is telling me that you would like to view pages in %1$s, but your %3$s are set to %2$s.'),
                        '<strong>' . \Locale::getDisplayName($localebrowser, $localeuser) . '</strong>',
                        '<strong>' . \Locale::getDisplayName($localeuser, $localeuser) . '</strong>',
                        '<a href="' . PAGE::PAGE_PROFILE . '">' . __('preferences') . '</a>'
                    );
                    ?>
                </p>

                <p>
                    <?php
                    \printf(
                        /** TRANSLATORS: %s: the users display name */
                        __('wishthis is available in %1$s different locales and also supports %2$s!'),
                        '<strong>' . \count($locales) . '</strong>',
                        '<strong>' . \Locale::getDisplayName($localebrowser, $localeuser) . '</strong>'
                    );
                    ?>
                </p>
            </div>
            <?php
        }

        $this->placeholders['SEGMENT_LOCALE'] = \trim(\ob_get_clean());

        parent::__construct();
    }

    private function getActionButtonsHtml(): string
    {
        global $database, $user;

        \ob_start();

        if ($user->isLoggedIn()) {
            ?>
            <div class="column">
                <a class="ui fluid primary button" href="<?= Page::PAGE_WISHLISTS ?>" title="<?= __('My lists') ?>">
                    <?= __('My lists') ?>
                </a>
            </div>

            <?php
            $lastWishlistQuery = $database->query(
                '  SELECT `wishlists`.*
                     FROM `wishes`
                     JOIN `wishlists` ON `wishes`.`wishlist` = `wishlists`.`id`
                     JOIN `users`     ON `wishlists`.`user`  = `users`.`id`
                    WHERE `users`.`id` = :user_id
                 ORDER BY `wishes`.`edited` DESC
                    LIMIT 1;',
                [
                    'user_id' => $user->getId(),
                ]
            );

            if (false !== $lastWishlistQuery) {
                $lastWishlist = $lastWishlistQuery->fetch();
                $href         = Page::PAGE_WISHLISTS . '&id=' . $lastWishlist['id'];
                $hrefAdd      = $href . '&wish_add=true';
                ?>
                <div class="column buttons">
                    <a class="ui left attached button" href="<?= $href ?>">
                        <?= $lastWishlist['name'] ?>
                    </a>
                    <a class="ui right attached icon button" href="<?= $hrefAdd ?>">
                        <i class="plus icon"></i>
                    </a>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="column">
                <a class="ui fluid primary button" href="<?= Page::PAGE_REGISTER ?>" title="<?= __('Register now') ?>">
                    <?= __('Register now') ?>
                </a>
            </div>

            <div class="column">
                <a class="ui fluid button" href="<?= Page::PAGE_LOGIN ?>" title="<?= __('Login') ?>">
                    <?= __('Login') ?>
                </a>
            </div>
                <?php
        }

        return \ob_get_clean();
    }

    public function default(): void
    {
        $this->placeholders['ACTION_BUTTONS'] = $this->getActionButtonsHtml();

        parent::render();
    }
}
