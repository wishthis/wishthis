<?php

namespace ConventionalChangelog\PackageBump;

use ConventionalChangelog\Helper\ShellCommand;
use ConventionalChangelog\Type\PackageBump;

class ComposerJson extends PackageBump
{
    /**
     * {@inheritdoc}
     */
    protected $fileName = 'composer.json';
    /**
     * {@inheritdoc}
     */
    protected $lockFiles = ['composer.lock'];
    /**
     * {@inheritdoc}
     */
    protected $fileType = 'json';

    /**
     * {@inheritdoc}
     */
    public function getVersion(): ?string
    {
        return $this->content->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(string $version): self
    {
        $this->content->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function save(): self
    {
        parent::save();
        /* Feature: https://github.com/marcocesarato/php-conventional-changelog/issues/11#issuecomment-819829828
        /* Bug: https://github.com/marcocesarato/php-conventional-changelog/issues/13
        if (ShellCommand::exists('composer')) {
            exec('cd ' . escapeshellarg($this->getPath()) . ' && composer update');
        }*/

        return $this;
    }
}
