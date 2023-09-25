<?php

/**
 * The home page.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Home'));
$page->header();
$page->bodyStart();
$page->navigation();

$user = User::getCurrent();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui doubling stackable grid">
            <div class="eleven wide column">
                <div class="ui segment">
                    <h2 class="ui header"><?= __('Make a wish') ?></h2>

                    <p><?= __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.') ?></p>

                    <div class="ui two column doubling stackable centered grid actions">
                        <?php if ($user->isLoggedIn()) { ?>
                            <div class="column">
                                <a class="ui fluid primary button"
                                   href="<?= Page::PAGE_WISHLISTS ?>"
                                   title="<?= __('My lists') ?>"
                                >
                                    <?= __('My lists') ?>
                                </a>
                            </div>

                            <?php
                            $lastWishlist      = null;
                            $lastWishlistQuery = $database->query(
                                '  SELECT `wishlists`.*
                                     FROM `wishes`
                                     JOIN `wishlists` ON `wishes`.`wishlist` = `wishlists`.`id`
                                     JOIN `users`     ON `wishlists`.`user`  = `users`.`id`
                                    WHERE `users`.`id` = :user_id
                                 ORDER BY `wishes`.`edited` DESC
                                    LIMIT 1;',
                                array(
                                    'user_id' => $user->getId(),
                                )
                            );

                            if (false !== $lastWishlistQuery && 1 === $lastWishlistQuery->rowCount()) {
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
                            ?>
                        <?php } else { ?>
                            <div class="column">
                                <a class="ui fluid primary button"
                                   href="<?= Page::PAGE_REGISTER ?>"
                                   title="<?= __('Register now') ?>"
                                >
                                    <?= __('Register now') ?>
                                </a>
                            </div>
                            <div class="column">
                                <a class="ui fluid button"
                                   href="<?= Page::PAGE_LOGIN ?>"
                                   title="<?= __('Login') ?>"
                                >
                                    <?= __('Login') ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="ui segment">
                    <h2 class="ui header"><?= __('Use case') ?></h2>

                    <p><?= __('Your birthday is coming up and you just created a wishlist with all the cool stuff you want. Your friends and family want to make sure you get something you are happy with so you send them your wishlist link and if anybody decides to fulfil one of your wishes, it will disappear for everybody else.') ?></p>
                </div>

                <div class="ui segment">
                    <h2 class="ui header"><?= __('Why wishthis?') ?></h2>

                    <p><?= sprintf(
                        __('wishthis is free and open source software. With free I don\'t just mean, you don\'t have to pay money to use it, but you are also not paying with your personal information and behaviour. Not only can anybody %sview and verify its code%s, I also encourage you to do so.'),
                        '<a href="https://github.com/grandeljay/wishthis" title="wishthis source code" target="_blank">',
                        '</a>'
                    ) ?></p>

                    <p><?= __('As an open source project it remains') ?></p>
                    <div class="flex why-wishthis">
                        <ul class="ui list">
                            <li class="item">
                                <i class="green check icon" aria-hidden="true"></i>
                                <div class="content"><?= __('free of advertisements') ?></div>
                            </li>
                            <?php
                            $popup_html = sprintf(
                                /** TRANSLATORS: %s: plausible */
                                __('see %s'),
                                '<a href=\'https://plausible.io\' target=\'_blank\'>' . __('plausible') . ' <i class=\'external alternate icon\'></i></a>'
                            );
                            ?>
                            <li class="item" data-html="<?= $popup_html ?>">
                                <i class="green check icon" aria-hidden="true"></i>
                                <div class="content">
                                    <?= __('without intrusive tracking') ?>
                                </div>
                            </li>
                            <li class="item">
                                <i class="green check icon" aria-hidden="true"></i>
                                <div class="content"><?= __('transparent') ?></div>
                            </li>
                        </ul>
                        <ul class="ui list">
                            <li class="item">
                                <i class="green check icon" aria-hidden="true"></i>
                                <div class="content"><?= __('privacy focused') ?></div>
                            </li>
                            <li class="item">
                                <i class="green check icon" aria-hidden="true"></i>
                                <div class="content"><?= __('open for feedback and suggestions') ?></div>
                            </li>
                        </ul>
                    </div>
                    <p><?= __('What you should also know') ?></p>
                    <div>
                        <ul class="ui list">
                            <li class="item">
                                <i class="orange info icon" aria-hidden="true"></i>
                                <div class="content">
                                    <?= __('affiliate links') ?>
                                    <p><?= __('amazon links are automatically converted to affiliate links to help support the project financially.') ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="ui segment">
                    <h2 class="ui header"><?= __('News') ?></h2>

                    <div class="ui relaxed divided list news">

                        <div class="item">
                            <i class="large rss middle aligned icon"></i>
                            <div class="content">
                                <div class="ui placeholder">
                                    <div class="paragraph">
                                        <div class="full line"></div>
                                        <div class="long line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <i class="large rss middle aligned icon"></i>
                            <div class="content">
                                <div class="ui placeholder">
                                    <div class="paragraph">
                                        <div class="short line"></div>
                                        <div class="very short line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <i class="large rss middle aligned icon"></i>
                            <div class="content">
                                <div class="ui placeholder">
                                    <div class="paragraph">
                                        <div class="medium line"></div>
                                        <div class="very long line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="five wide column">

                <div class="ui segment">
                    <h2 class="ui header"><?= __('Statistics') ?></h2>

                    <p><?= __('Join the others and get started now!') ?></p>

                    <div class="ui stackable statistics">
                        <div class="statistic" id="wishes">
                            <div class="value"><?= __('N. A.') ?></div>
                            <div class="label"><?= __('Wishes') ?></div>
                        </div>

                        <div class="statistic" id="wishlists">
                            <div class="value"><?= __('N. A.') ?></div>
                            <div class="label"><?= __('Wishlists') ?></div>
                        </div>

                        <div class="statistic" id="users">
                            <div class="value"><?= __('N. A.') ?></div>
                            <div class="label"><?= __('Registered users') ?></div>
                        </div>
                    </div>
                </div>

                <?php
                $locale_browser = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : DEFAULT_LOCALE;
                $locale_user    = $user->getLocale();

                if ($user->isLoggedIn() && $locale_browser !== $locale_user && in_array($locale_browser, $locales, true)) {
                    ?>
                    <div class="ui segment">
                        <h2 class="ui header"><?= __('Hey, you') ?></h2>

                        <p>
                            <?php
                            printf(
                                /** TRANSLATORS: %s: the users display name */
                                __('Yes, I mean you, %s.'),
                                $user->getDisplayName()
                            );
                            ?>
                        </p>

                        <p>
                            <?php
                            printf(
                                /** TRANSLATORS: %1$s: Locale, e. g. German (Germany), %2$s: Locale, e. g. English (United Kingdom) %3$s: preferences */
                                __('Your browser is telling me that you would like to view pages in %1$s, but your %3$s are set to %2$s.'),
                                '<strong>' . \Locale::getDisplayName($locale_browser, $locale_user) . '</strong>',
                                '<strong>' . \Locale::getDisplayName($locale_user, $locale_user) . '</strong>',
                                '<a href="' . PAGE::PAGE_PROFILE . '">' . __('preferences') . '</a>'
                            );
                            ?>
                        </p>

                        <p>
                            <?php
                            printf(
                                /** TRANSLATORS: %s: the users display name */
                                __('wishthis is available in %1$s different locales and also supports %2$s!'),
                                '<strong>' . count($locales) . '</strong>',
                                '<strong>' . \Locale::getDisplayName($locale_browser, $locale_user) . '</strong>'
                            );
                            ?>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
