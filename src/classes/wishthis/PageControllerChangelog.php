<?php

namespace wishthis;

class PageControllerChangelog extends PageController
{
    protected string $id                   = 'changelog';
    protected bool $requiresAuthentication = false;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Changelog');

        parent::__construct();
    }


    public function default(): void
    {
        $parsedown           = new \Parsedown();
        $changelogsDirectory = ROOT . '/changelogs';

        $changelogHeadFilepath = $changelogsDirectory . '/changelog.md';
        $changelogHeadMarkdown = \file_get_contents($changelogHeadFilepath);
        $changelogHeadHtml     = $parsedown->text($changelogHeadMarkdown);

        $this->placeholders['CHANGELOG_HEAD_HTML'] = $changelogHeadHtml;

        $changelogs = \scandir($changelogsDirectory, \SCANDIR_SORT_DESCENDING);
        $changelogs = \array_map(
            function (string $filename) use (
                $changelogsDirectory,
                $changelogHeadFilepath,
            ) {
                $filepath = $changelogsDirectory . '/' . $filename;

                if ($filepath === $changelogHeadFilepath) {
                    return false;
                }

                return $filepath;
            },
            $changelogs
        );
        $changelogs = \array_filter($changelogs, '\is_file');

        $menuItems = [];

        foreach ($changelogs as $filepath) {
            $filenameSanitised = \pathinfo($filepath, \PATHINFO_FILENAME);
            $firstLine         = \fgets(\fopen($filepath, 'r'));
            $label             = \preg_replace('/[^a-zA-Z0-9\-\.]/', '', $firstLine);

            $menuItems[] = \sprintf(
                '<a class="item" data-tab="%1$s">%2$s</a>',
                $filenameSanitised,
                $label
            );
        }

        $this->placeholders['CHANGELOG_MENU_ITEMS'] = \implode(\PHP_EOL, $menuItems);

        $menuTabs = [];

        foreach ($changelogs as $filepath) {
            $filenameSanitised = \pathinfo($filepath, \PATHINFO_FILENAME);
            \ob_start();
            ?>
            <div class="ui tab" data-tab="<?= $filenameSanitised ?>">
                <div class="ui segments">
                    <?php
                    $text = \file_get_contents($filepath);
                    $text = \preg_replace('/(#(\d+))/', '<a href="https://github.com/wishthis/wishthis/issues/$2">$1</a>', $text);
                    $text = \preg_replace('/(([a-f0-9]{7}))/', '<a href="https://github.com/wishthis/wishthis/commit/$2">$1</a>', $text);
                    ?>
                    <div class="ui segment"><?= $parsedown->text($text); ?></div>
                </div>
            </div>
            <?php
            $menuTabs[] = \ob_get_clean();
        }

        $this->placeholders['CHANGELOG_MENU_TABS'] = \implode(\PHP_EOL, $menuTabs);

        parent::render();
    }
}
