<?php

namespace ConventionalChangelog\PackageBump;

use ConventionalChangelog\Type\PackageBump;

class PackageJson extends PackageBump
{
    /**
     * {@inheritdoc}
     */
    protected $fileName = 'package.json';
    /**
     * {@inheritdoc}
     */
    protected $lockFiles = ['package.lock', 'yarn.lock', 'pnpm-lock.yaml'];
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
}
