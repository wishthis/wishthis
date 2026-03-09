<?php

namespace wishthis;

class PageController
{
    protected string $id;
    protected string $template;
    protected string $metaTitle;
    protected string $pageTitle;

    protected array $placeholders;

    public function __construct()
    {
        if (!isset($this->metaTitle)) {
            $this->metaTitle = $this->pageTitle;
        }

        $this->setPlaceholders();
    }

    private function setPlaceholders(): void
    {
        $this->setPlaceholderPageLocale();

        $this->setPlaceholderPageMetaTitle();
        $this->setPlaceholderPageMetaDescription();
        $this->setPlaceholderPageMetaLinkPreview();
        $this->setPlaceholderPageMetaAlternates();
        $this->setPlaceholderPageMetaHost();
        $this->setPlaceholderPageMetaUrl();
        $this->setPlaceholderPageMetaCanonical();

        $this->setPlaceholderPageTitle();

        $this->setPlaceholderPageMetaStylsheets();
        $this->setPlaceholderPageMetaScripts();

        $this->setPlaceholderPageNavigation();
    }

    private function setPlaceholderPageLocale(): void
    {
        global $locale;

        $this->placeholders['PAGE_LOCALE'] = $locale;
    }

    private function setPlaceholderPageMetaTitle(): void
    {
        $this->placeholders['PAGE_META_TITLE'] = \sprintf(
            '%1$s - wishthis',
            $this->metaTitle
        );
    }

    private function setPlaceholderPageMetaDescription(): void
    {
        $this->placeholders['PAGE_META_DESCRIPTION'] = __(
            'wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.'
        );
    }

    private function setPlaceholderPageMetaLinkPreview(): void
    {
        $this->placeholders['PAGE_META_LINK_PREVIEW'] = \sprintf(
            'https://%1$s/src/assets/img/link-previews/default.png',
            $_SERVER['HTTP_HOST']
        );
    }

    private function setPlaceholderPageMetaAlternates(): void
    {
        global $locales;

        $pageMetaAlternates = [];

        foreach ($locales as $l) {
            if ('{{PAGE_LOCALE}}' !== $l) {
                $pageMetaAlternates[] = $l;
            }
        }

        $pageMetaAlternates = \array_map(
            function (string $pageMetaAlternate): string {
                return \sprintf(
                    '    <meta property="og:locale:alternate" content="%1$s">',
                    $pageMetaAlternate
                );
            },
            $pageMetaAlternates
        );

        $this->placeholders['PAGE_META_ALTERNATES'] = \implode(
            \PHP_EOL,
            $pageMetaAlternates
        );
    }

    private function setPlaceholderPageMetaHost(): void
    {
        $this->placeholders['PAGE_META_HOST'] = $_SERVER['HTTP_HOST'];
    }

    private function setPlaceholderPageMetaUrl(): void
    {
        $this->placeholders['PAGE_META_URL'] = $_SERVER['REQUEST_SCHEME']
                                             . '://'
                                             . $_SERVER['HTTP_HOST']
                                             . $_SERVER['REQUEST_URI'];
    }

    private function setPlaceholderPageMetaCanonical(): void
    {
        if (\defined('CHANNELS') && \is_array(CHANNELS)) {
            $channels = CHANNELS;
            $stable   = \reset($channels);

            $this->placeholders['PAGE_META_CANONICAL'] = \sprintf(
                '    <link rel="canonical" href="%1$s">',
                $_SERVER['REQUEST_SCHEME'] . '://' . $stable['host'] . $_SERVER['REQUEST_URI']
            );
        } else {
            $this->placeholders['PAGE_META_CANONICAL'] = \sprintf(
                '    <link rel="canonical" href="%1$s">',
                $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']
            );
        }
    }

    private function setPlaceholderPageTitle(): void
    {
        $this->placeholders['PAGE_TITLE'] = $this->pageTitle;
    }

    private function setPlaceholderPageMetaStylsheets(): void
    {
        $stylesheets             = [
            'fomantic-ui' => 'semantic/dist/semantic.min.css',
            'default'     => 'src/assets/css/default.css',
            'dark'        => 'src/assets/css/default/dark.css',
        ];
        $stylesheetForPage       = \sprintf('src/assets/css/%1$s.css', $this->id);
        $stylesheetForPageExists = \file_exists($stylesheetForPage);

        if ($stylesheetForPageExists) {
            $stylesheets[] = $stylesheetForPage;
        }

        $this->placeholders['PAGE_META_STYLESHEETS'] = \implode(
            \PHP_EOL,
            \array_map(
                function (string $stylesheetPath): string {
                    $styleshettHash = \hash_file(
                        'crc32',
                        \ROOT . '/' . $stylesheetPath
                    );

                    return \sprintf(
                        '    <link rel="stylesheet" type="text/css" href="%1$s?v=%2$s">',
                        $stylesheetPath,
                        $styleshettHash
                    );
                },
                $stylesheets
            )
        );
    }

    private function setPlaceholderPageMetaScripts(): void
    {
        $scripts             = [
            'j-query'     => 'node_modules/jquery/dist/jquery.min.js',
            'fomantic-ui' => 'semantic/dist/semantic.min.js',
            'default'     => 'src/assets/js/default.js',
        ];
        $scriptForPage       = \sprintf('src/assets/js/%1$s.js', $this->id);
        $scriptForPageExists = \file_exists($scriptForPage);

        if ($scriptForPageExists) {
            $scripts[] = $scriptForPage;
        }

        $this->placeholders['PAGE_META_SCRIPTS'] = \implode(
            \PHP_EOL,
            \array_map(
                function (string $scriptPath): string {
                    $scriptHash = \hash_file(
                        'crc32',
                        \ROOT . '/' . $scriptPath
                    );

                    return \sprintf(
                        '    <script defer type="text/javascript" src="%1$s?v=%2$s"></script>',
                        $scriptPath,
                        $scriptHash
                    );
                },
                $scripts
            )
        );

        if (\defined('PLAUSIBLE') && true === PLAUSIBLE) {
            $this->placeholders['PAGE_META_SCRIPTS'] .= \PHP_EOL . '    ' . \sprintf(
                '<script defer type="text/javascript" data-domain="%1$s" src="https://plausible.io/js/plausible.js"></script>',
                $_SERVER['HTTP_HOST']
            );
        }

        \ob_start();
        require \sprintf('%1$s/src/assets/js/inline.js.php', \ROOT);
        $this->placeholders['PAGE_META_SCRIPTS'] .=  \PHP_EOL . '    ' . \ob_get_clean();
    }

    private function setPlaceholderPageNavigation(): void
    {
        $user = User::getCurrent();

        $wishlists = Navigation::Wishlists->value;
        $blog      = Navigation::Blog->value;
        $system    = Navigation::System->value;
        $settings  = Navigation::Settings->value;
        $account   = Navigation::Account->value;
        $login     = Navigation::Login->value;
        $register  = Navigation::Register->value;

        $pages = [
            $blog    => [
                'text'      => __('Blog'),
                'alignment' => 'left',
                'items'     => [
                    [
                        'text' => __('Blog'),
                        'url'  => Page::PAGE_BLOG,
                        'icon' => 'rss',
                    ],
                ],
            ],
            $system  => [
                'text'      => __('System'),
                'icon'      => 'wrench',
                'alignment' => 'right',
                'items'     => [],
            ],
            $account => [
                'text'      => __('Account'),
                'icon'      => 'user circle',
                'alignment' => 'right',
                'items'     => [],
            ],
        ];

        if ($user->isLoggedIn()) {
            $pages[$wishlists] = [
                'text'      => __('Wishlists'),
                'alignment' => 'left',
                'items'     => [
                    [
                        'text' => __('My lists'),
                        'url'  => Page::PAGE_WISHLISTS,
                        'icon' => 'list',
                    ],
                    [
                        'text' => __('Remembered lists'),
                        'url'  => Page::PAGE_WISHLISTS_SAVED,
                        'icon' => 'heart',
                    ],
                ],
            ];
        }

        if ($user->isLoggedIn()) {
            $pages[$account]['items'][] = [
                'text' => __('Profile'),
                'url'  => Page::PAGE_PROFILE,
                'icon' => 'user circle alternate',
            ];
            if (100 === $user->getPower()) {
                $pages[$account]['items'][] = [
                    'text' => __('Login as'),
                    'url'  => Page::PAGE_LOGIN_AS,
                    'icon' => 'sign out alternate',
                ];
            }
            $pages[$account]['items'][] = [
                'text' => __('Logout'),
                'url'  => Page::PAGE_LOGOUT,
                'icon' => 'sign out alternate',
            ];
        } else {
            $pages[$login] = [
                'text'      => __('Login'),
                'alignment' => 'right',
                'items'     => [
                    [
                        'text' => __('Login'),
                        'url'  => Page::PAGE_LOGIN,
                        'icon' => 'sign in alternate',
                    ],
                ],
            ];

            $registrationDisabled = \defined('DISABLE_USER_REGISTRATION') && true === DISABLE_USER_REGISTRATION;

            if (!$registrationDisabled) {
                $pages[$register] = [
                    'text'      => __('Register'),
                    'alignment' => 'right',
                    'items'     => [
                        [
                            'text' => __('Register'),
                            'url'  => Page::PAGE_REGISTER,
                            'icon' => 'user plus alternate',
                        ],
                    ],
                ];
            }
        }

        if (100 === $user->getPower()) {
            $pages[$system]['items'][] = [
                'text' => __('Settings'),
                'url'  => Page::PAGE_SETTINGS,
                'icon' => 'cog',
            ];
        }

        \ksort($pages);

        if ('home' === $this->id) {
            $logo = \file_get_contents(ROOT . '/src/assets/img/logo-animation.svg');
        } else {
            $logo = \file_get_contents(ROOT . '/src/assets/img/logo.svg');
        }

        \ob_start();
        ?>
        <div class="ui attached stackable vertical menu sidebar">
            <div class="ui container">

                <a class="item home" href="<?= Page::PAGE_HOME ?>"><?= $logo ?></a>

                <?php foreach ($pages as $page) { ?>
                    <?php foreach ($page['items'] as $item) { ?>
                        <a class="item" href="<?= $item['url'] ?>">
                            <i class="<?= $item['icon'] ?> icon"></i>
                            <?= $item['text'] ?>
                        </a>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>

        <div class="pusher">
            <div class="ui attached menu desktop">
                <div class="ui container">
                    <a class="item home" href="<?= Page::PAGE_HOME ?>"><?= $logo ?></a>

                    <?php foreach ($pages as $page) { ?>
                        <?php if ('left' === $page['alignment']) { ?>
                            <?php if (\count($page['items']) > 1) { ?>
                                <div class="ui simple dropdown item">
                                    <?php if (isset($page['icon'])) { ?>
                                        <i class="<?= $page['icon'] ?> icon"></i>
                                    <?php } ?>

                                    <?= $page['text'] ?>

                                    <i class="dropdown icon"></i>

                                    <div class="menu">
                                        <?php foreach ($page['items'] as $item) { ?>
                                            <a class="item" href="<?= $item['url'] ?>">
                                                <i class="<?= $item['icon'] ?> icon"></i>
                                                <?= $item['text'] ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <?php foreach ($page['items'] as $item) { ?>
                                    <a class="item" href="<?= $item['url'] ?>">
                                        <i class="<?= $item['icon'] ?> icon"></i>
                                        <?= $item['text'] ?>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <div class="right menu">
                        <?php foreach ($pages as $page) { ?>
                            <?php if ('right' === $page['alignment']) { ?>
                                <?php if (\count($page['items']) > 1) { ?>
                                    <div class="ui simple dropdown item">
                                        <?php if (isset($page['icon'])) { ?>
                                            <i class="<?= $page['icon'] ?> icon"></i>
                                        <?php } ?>

                                        <?= $page['text'] ?>

                                        <i class="dropdown icon"></i>

                                        <div class="menu">
                                            <?php foreach ($page['items'] as $item) { ?>
                                                <a class="item" href="<?= $item['url'] ?>">
                                                    <i class="<?= $item['icon'] ?> icon"></i>
                                                    <?= $item['text'] ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <?php foreach ($page['items'] as $item) { ?>
                                        <a class="item" href="<?= $item['url'] ?>">
                                            <i class="<?= $item['icon'] ?> icon"></i>
                                            <?= $item['text'] ?>
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="ui attached stackable menu toggle">
                <div class="ui container">
                    <a class="item">
                        <i class="hamburger icon"></i>
                        Menu
                    </a>
                </div>
            </div>
        </div>
        <?php
        $this->placeholders['PAGE_NAVIGATION'] = \ob_get_clean();
    }

    protected function render(): void
    {
        $directoryTemplates = \ROOT . '/src/templates/' . $this->template;
        $templateContent    = \file_get_contents($directoryTemplates);

        foreach ($this->placeholders as $placeholder => $replacement) {
            $templateContent = \str_replace(
                \sprintf('{{%1$s}}', $placeholder),
                $replacement,
                $templateContent
            );
        }

        echo $templateContent;
    }
}
