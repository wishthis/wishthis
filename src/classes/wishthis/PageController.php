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

        global $locales, $locale;

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

        $this->placeholders['PAGE_LOCALE']            = $locale;
        $this->placeholders['PAGE_META_TITLE']        = \sprintf('%1$s - wishthis', $this->metaTitle);
        $this->placeholders['PAGE_META_DESCRIPTION']  = __('wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion.');
        $this->placeholders['PAGE_META_LINK_PREVIEW'] = \sprintf('https://%1$s/src/assets/img/link-previews/default.png', $_SERVER['HTTP_HOST']);
        $this->placeholders['PAGE_META_ALTERNATES']   = \implode(\PHP_EOL, $pageMetaAlternates);
        $this->placeholders['PAGE_META_HOST']         = $_SERVER['HTTP_HOST'];
        $this->placeholders['PAGE_META_URL']          = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

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

        $this->placeholders['PAGE_TITLE'] = $this->pageTitle;

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
