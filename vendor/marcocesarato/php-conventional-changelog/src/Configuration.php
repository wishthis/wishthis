<?php

namespace ConventionalChangelog;

class Configuration
{
    /**
     * Working dir.
     *
     * @var string
     */
    protected $root = './';

    /**
     * Changelog filename.
     *
     * @var string
     */
    protected $path = 'CHANGELOG.md';

    /**
     * Header description.
     *
     * @var string
     */
    protected $headerTitle = 'Changelog';

    /**
     * Header title.
     *
     * @var string
     */
    protected $headerDescription = 'All notable changes to this project will be documented in this file.';

    /**
     * Preset of types allowed on changelog and labels.
     * Sorting must be preserved.
     *
     * @var string[][]
     */
    protected $preset = [
        'feat' => ['label' => 'Features', 'description' => 'New features'],
        'perf' => ['label' => 'Performance Improvements', 'description' => 'Code changes that improves performance'],
        'fix' => ['label' => 'Bug Fixes', 'description' => 'Bugs and issues resolution'],
        'refactor' => ['label' => 'Code Refactoring', 'description' => 'A code change that neither fixes a bug nor adds a feature'],
        'style' => ['label' => 'Styles', 'description' => 'Changes that do not affect the meaning of the code'],
        'test' => ['label' => 'Tests', 'description' => 'Adding missing tests or correcting existing tests'],
        'build' => ['label' => 'Builds', 'description' => 'Changes that affect the build system or external dependencies '],
        'ci' => ['label' => 'Continuous Integrations', 'description' => 'Changes to CI configuration files and scripts'],
        'docs' => ['label' => 'Documentation', 'description' => 'Documentation changes'],
        'chore' => ['label' => 'Chores', 'description' => "Other changes that don't modify the source code or test files"],
        'revert' => ['label' => 'Reverts', 'description' => 'Reverts a previous commit'],
    ];

    /**
     * Key of breaking changes.
     *
     * @var string
     */
    public const BREAKING_CHANGES_TYPE = 'breaking_changes';

    /**
     * Preset of breaking changes.
     *
     * @var string[][]
     */
    protected $breakingChangesPreset = ['label' => '⚠ BREAKING CHANGES', 'description' => 'Code changes that potentially causes other components to fail'];

    /**
     * Types allowed on changelog.
     *
     * @var string[][]
     */
    protected $types = [];

    /**
     * Bump package.
     *
     * @var bool
     */
    protected $packageBump = true;

    /**
     * Bump packages.
     *
     * @var array
     */
    protected $packageBumps = [];

    /**
     * Commit package lock file.
     *
     * @var bool
     */
    protected $packageLockCommit = true;

    /**
     * Ignore message commit patterns.
     *
     * @var string[]
     */
    protected $ignorePatterns = [
        '/^chore\(release\):/i',
    ];

    /**
     * Ignore types.
     *
     * @var string[]
     */
    protected $ignoreTypes = ['build', 'chore', 'ci', 'docs', 'perf', 'refactor', 'revert', 'style', 'test'];

    /**
     * Tag prefix.
     *
     * @var string
     */
    protected $tagPrefix = 'v';

    /**
     * Skip tag.
     *
     * @var bool
     */
    protected $skipTag = false;

    /**
     * Skip bump.
     *
     * @var bool
     */
    protected $skipBump = false;

    /**
     * Skip verify.
     *
     * @var bool
     */
    protected $skipVerify = false;

    /**
     * Render text instead of links.
     *
     * @var bool
     */
    protected $disableLinks = false;

    /**
     * Hidden references.
     *
     * @var bool
     */
    protected $hiddenReferences = false;

    /**
     * Hidden mentions.
     *
     * @var bool
     */
    protected $hiddenMentions = false;

    /**
     * Hidden hash.
     *
     * @var bool
     */
    protected $hiddenHash = false;

    /**
     * Tag suffix.
     *
     * @var string
     */
    protected $tagSuffix = '';

    /**
     * The URL protocol of all repository urls on changelogs.
     *
     * @var string
     */
    protected $urlProtocol = 'https';

    /**
     * Date format.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * Allows configurable changelog version header format.
     *
     * @var string
     */
    protected $changelogVersionFormat = '## {{version}} ({{date}})';

    /**
     * A URL representing a specific commit at a hash.
     *
     * @var string
     */
    protected $commitUrlFormat = '{{host}}/{{owner}}/{{repository}}/commit/{{hash}}';

    /**
     * A URL representing the comparison between two git sha.
     *
     * @var string
     */
    protected $compareUrlFormat = '{{host}}/{{owner}}/{{repository}}/compare/{{previousTag}}...{{currentTag}}';

    /**
     * A URL representing the issue format (allowing a different URL format to be swapped in for Gitlab, Bitbucket, etc).
     *
     * @var string
     */
    protected $issueUrlFormat = '{{host}}/{{owner}}/{{repository}}/issues/{{id}}';

    /**
     * A URL representing the a user's profile URL on GitHub, Gitlab, etc. This URL is used for substituting @abc with https://github.com/abc in commit messages.
     *
     * @var string
     */
    protected $userUrlFormat = '{{host}}/{{user}}';

    /**
     * A string to be used to format the auto-generated release commit message.
     *
     * @var string
     */
    protected $releaseCommitMessageFormat = 'chore(release): {{currentTag}}';

    /**
     * Prettify scope.
     *
     * @var bool
     */
    protected $prettyScope = true;

    /**
     * Hide Version Separator.
     *
     * @var bool
     */
    protected $hiddenVersionSeparator = false;

    /**
     * Sort by options and orientation.
     *
     * @var string[]
     */
    protected const SORT_BY = [
        'date' => 'DESC',
        'subject' => 'ASC',
        'authorName' => 'ASC',
        'authorEmail' => 'ASC',
        'authorDate' => 'DESC',
        'committerName' => 'ASC',
        'committerEmail' => 'ASC',
        'committerDate' => 'DESC',
    ];

    /**
     * Commit sorting.
     *
     * @var string
     */
    protected $sortBy = 'subject';

    /**
     * Pre run.
     *
     * @var mixed
     */
    protected $preRun;

    /**
     * Post run.
     *
     * @var mixed
     */
    protected $postRun;

    /**
     * Constructor.
     */
    public function __construct(array $settings = [])
    {
        $this->setRoot();
        $this->setTypes($this->preset);
        $this->fromArray($settings);
    }

    /**
     * From array.
     */
    public function fromArray(array $array)
    {
        $defaults = [
            'root' => null,
            'headerTitle' => $this->getHeaderTitle(),
            'headerDescription' => $this->getHeaderDescription(),
            'sortBy' => $this->getSortBy(),
            'path' => $this->getPath(),
            'preset' => $this->getPreset(),
            'types' => [],
            'packageBump' => $this->isPackageBump(),
            'packageBumps' => [],
            'packageLockCommit' => $this->isPackageLockCommit(),
            'ignoreTypes' => $this->getIgnoreTypes(),
            'ignorePatterns' => $this->getIgnorePatterns(),
            'tagPrefix' => $this->getTagPrefix(),
            'tagSuffix' => $this->getTagSuffix(),
            'skipBump' => $this->skipBump(),
            'skipTag' => $this->skipTag(),
            'skipVerify' => $this->skipVerify(),
            'disableLinks' => $this->isDisableLinks(),
            'hiddenHash' => $this->isHiddenHash(),
            'hiddenMentions' => $this->isHiddenMentions(),
            'hiddenReferences' => $this->isHiddenReferences(),
            'prettyScope' => $this->isPrettyScope(),
            'urlProtocol' => $this->getUrlProtocol(),
            'dateFormat' => $this->getDateFormat(),
            'changelogVersionFormat' => $this->getChangelogVersionFormat(),
            'commitUrlFormat' => $this->getCommitUrlFormat(),
            'compareUrlFormat' => $this->getCompareUrlFormat(),
            'issueUrlFormat' => $this->getIssueUrlFormat(),
            'userUrlFormat' => $this->getUserUrlFormat(),
            'hiddenVersionSeparator' => $this->isHiddenVersionSeparator(),
            'releaseCommitMessageFormat' => $this->getReleaseCommitMessageFormat(),
            'preRun' => $this->getPreRun(),
            'postRun' => $this->getPostRun(),
        ];

        $params = array_replace_recursive($defaults, $array);

        // Ignore Types
        if (isset($array['ignoreTypes'])) {
            $params['ignoreTypes'] = $array['ignoreTypes']; // Overwrite ignored types
        }

        // Set Types (overwrite ignored types)
        if (!empty($array['types'])) {
            foreach ($this->preset as $type => $value) {
                if (!in_array($type, $array['types'])) {
                    if (isset($params['preset'][$type])) {
                        unset($params['preset'][$type]);
                    }
                } else {
                    $params['preset'][$type] = $value;
                }
            }
        }

        // Add breaking changes
        $breakingPreset = $this->getBreakingChangesPreset();
        $params['preset'] = array_merge($breakingPreset, $params['preset']);

        $this
            // Paths
            ->setRoot($params['root'])
            ->setPath($params['path'])
            // Types
            ->setIgnorePatterns($params['ignorePatterns'])
            ->setIgnoreTypes($params['ignoreTypes'])
            ->setTypes($params['preset'])
            // Package
            ->setPackageBump($params['packageBump'])
            ->setPackageBumps($params['packageBumps'])
            ->setPackageLockCommit($params['packageLockCommit'])
            // Document
            ->setHeaderTitle($params['headerTitle'])
            ->setHeaderDescription($params['headerDescription'])
            ->setSortBy($params['sortBy'])
            // Tag
            ->setTagPrefix($params['tagPrefix'])
            ->setTagSuffix($params['tagSuffix'])
            // Skips
            ->setSkipBump($params['skipBump'])
            ->setSkipTag($params['skipTag'])
            ->setSkipVerify($params['skipVerify'])
            // Links
            ->setDisableLinks($params['disableLinks'])
            // Hidden
            ->setHiddenHash($params['hiddenHash'])
            ->setHiddenMentions($params['hiddenMentions'])
            ->setHiddenReferences($params['hiddenReferences'])
            ->setHiddenVersionSeparator($params['hiddenVersionSeparator'])
            // Formats
            ->setPrettyScope($params['prettyScope'])
            ->setUrlProtocol($params['urlProtocol'])
            ->setDateFormat($params['dateFormat'])
            ->setCommitUrlFormat($params['commitUrlFormat'])
            ->setCompareUrlFormat($params['compareUrlFormat'])
            ->setIssueUrlFormat($params['issueUrlFormat'])
            ->setUserUrlFormat($params['userUrlFormat'])
            ->setReleaseCommitMessageFormat($params['releaseCommitMessageFormat'])
            ->setDisableLinks($params['disableLinks'])
            ->setChangelogVersionFormat($params['changelogVersionFormat'])
            // Hooks
            ->setPreRun($params['preRun'])
            ->setPostRun($params['postRun']);
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return array_keys($this->types);
    }

    /**
     * @return string[]
     */
    public function getAllowedTypes(): array
    {
        $types = $this->types;
        unset($types['breaking_changes']);

        return array_keys($types);
    }

    public function getTypeLabel(string $type): string
    {
        return $this->types[$type]['label'];
    }

    public function getTypeDescription(string $type): string
    {
        return $this->types[$type]['description'] ?? '';
    }

    /**
     * @param string[][] $types
     */
    public function setTypes(array $types): self
    {
        $ignoreTypes = $this->getIgnoreTypes();
        foreach ($ignoreTypes as $type) {
            unset($types[$type]);  // Unset excluded types
        }

        $this->types = $types;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getHeaderTitle(): string
    {
        return $this->headerTitle;
    }

    public function setHeaderTitle(string $headerTitle): self
    {
        $this->headerTitle = $headerTitle;

        return $this;
    }

    public function getHeaderDescription(): string
    {
        return $this->headerDescription;
    }

    public function setHeaderDescription(string $headerDescription): self
    {
        $this->headerDescription = $headerDescription;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIgnorePatterns(): array
    {
        return $this->ignorePatterns;
    }

    /**
     * @param string[] $ignorePatterns
     */
    public function setIgnorePatterns(array $ignorePatterns): self
    {
        foreach ($ignorePatterns as $key => $pattern) {
            if (!$this->isRegex($pattern)) {
                $ignorePatterns[$key] = '#' . preg_quote($pattern, '#') . '#i';
            }
        }
        $this->ignorePatterns = $ignorePatterns;

        return $this;
    }

    /**
     * @return string[][]
     */
    public function getPreset(): array
    {
        return array_merge($this->getBreakingChangesPreset(), $this->preset);
    }

    /**
     * @param string[] $ignoreTypes
     */
    public function setIgnoreTypes(array $ignoreTypes): self
    {
        $types = $this->getTypes();
        foreach ($ignoreTypes as $type) {
            unset($types[$type]);  // Unset excluded types
        }

        $this->ignoreTypes = $ignoreTypes;
        $this->types = $types;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIgnoreTypes(): array
    {
        return $this->ignoreTypes;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     */
    public function setRoot(?string $root = null): self
    {
        if (empty($root) || !is_dir($root)) {
            $root = getcwd();
        }

        $this->root = $root;

        return $this;
    }

    public function getTagPrefix(): string
    {
        return $this->tagPrefix;
    }

    public function setTagPrefix(string $tagPrefix): self
    {
        $this->tagPrefix = $tagPrefix;

        return $this;
    }

    public function getTagSuffix(): string
    {
        return $this->tagSuffix;
    }

    public function setTagSuffix(string $tagSuffix): self
    {
        $this->tagSuffix = $tagSuffix;

        return $this;
    }

    public function skipTag(): bool
    {
        return $this->skipTag;
    }

    public function setSkipTag(bool $skipTag): self
    {
        $this->skipTag = $skipTag;

        return $this;
    }

    public function skipBump(): bool
    {
        return $this->skipBump;
    }

    public function setSkipBump(bool $skipBump): self
    {
        $this->skipBump = $skipBump;

        return $this;
    }

    public function skipVerify(): bool
    {
        return $this->skipVerify;
    }

    public function setSkipVerify(bool $skipVerify): self
    {
        $this->skipVerify = $skipVerify;

        return $this;
    }

    /**
     * @return \string[][]
     */
    public function getBreakingChangesPreset(): array
    {
        return [self::BREAKING_CHANGES_TYPE => $this->breakingChangesPreset];
    }

    public function getCommitUrlFormat(): string
    {
        return $this->commitUrlFormat;
    }

    public function setCommitUrlFormat(string $commitUrlFormat): self
    {
        $this->commitUrlFormat = $commitUrlFormat;

        return $this;
    }

    public function getCompareUrlFormat(): string
    {
        return $this->compareUrlFormat;
    }

    public function setCompareUrlFormat(string $compareUrlFormat): self
    {
        $this->compareUrlFormat = $compareUrlFormat;

        return $this;
    }

    public function getIssueUrlFormat(): string
    {
        return $this->issueUrlFormat;
    }

    public function setIssueUrlFormat(string $issueUrlFormat): self
    {
        $this->issueUrlFormat = $issueUrlFormat;

        return $this;
    }

    public function getUserUrlFormat(): string
    {
        return $this->userUrlFormat;
    }

    public function setUserUrlFormat(string $userUrlFormat): self
    {
        $this->userUrlFormat = $userUrlFormat;

        return $this;
    }

    public function getReleaseCommitMessageFormat(): string
    {
        return $this->releaseCommitMessageFormat;
    }

    public function setReleaseCommitMessageFormat(string $releaseCommitMessageFormat): self
    {
        $this->releaseCommitMessageFormat = $releaseCommitMessageFormat;

        return $this;
    }

    public function getUrlProtocol(): string
    {
        return $this->urlProtocol;
    }

    public function setUrlProtocol(string $urlProtocol): self
    {
        $this->urlProtocol = $urlProtocol;

        return $this;
    }

    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    public function setDateFormat(string $dateFormat): self
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    public function isDisableLinks(): bool
    {
        return $this->disableLinks;
    }

    public function setDisableLinks(bool $disableLinks): self
    {
        $this->disableLinks = $disableLinks;

        return $this;
    }

    public function getChangelogVersionFormat(): string
    {
        return $this->changelogVersionFormat;
    }

    public function setChangelogVersionFormat($changelogVersionFormat): self
    {
        $this->changelogVersionFormat = $changelogVersionFormat;

        return $this;
    }

    public function isHiddenReferences(): bool
    {
        return $this->hiddenReferences;
    }

    public function setHiddenReferences(bool $hiddenReferences): self
    {
        $this->hiddenReferences = $hiddenReferences;

        return $this;
    }

    public function isHiddenMentions(): bool
    {
        return $this->hiddenMentions;
    }

    public function setHiddenMentions(bool $hiddenMentions): self
    {
        $this->hiddenMentions = $hiddenMentions;

        return $this;
    }

    public function isHiddenHash(): bool
    {
        return $this->hiddenHash;
    }

    public function setHiddenHash(bool $hiddenHash): self
    {
        $this->hiddenHash = $hiddenHash;

        return $this;
    }

    public function isPrettyScope(): bool
    {
        return $this->prettyScope;
    }

    public function setPrettyScope(bool $prettyScope): self
    {
        $this->prettyScope = $prettyScope;

        return $this;
    }

    public function getSortBy(): string
    {
        $sortBy = $this->sortBy;
        if ($sortBy === 'date') {
            $sortBy = 'committerDate';
        }

        return $sortBy;
    }

    public function setSortBy(string $sortBy): self
    {
        $sortBy = trim($sortBy);
        if (!array_key_exists($sortBy, self::SORT_BY)) {
            $sortBy = 'date';
        }
        $this->sortBy = $sortBy;

        return $this;
    }

    public function getSortOrientation(string $sort): string
    {
        return self::SORT_BY[$sort];
    }

    /**
     * @param mixed $hook
     */
    public function runHook($hook)
    {
        if (is_string($hook)) {
            system($hook);
        } elseif (is_callable($hook)) {
            call_user_func($hook);
        }
    }

    /**
     * @return mixed
     */
    public function getPreRun()
    {
        return $this->preRun;
    }

    public function preRun()
    {
        $this->runHook($this->preRun);
    }

    /**
     * @param mixed $preRun
     */
    public function setPreRun($preRun): self
    {
        $this->preRun = $preRun;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostRun()
    {
        return $this->postRun;
    }

    public function postRun()
    {
        $this->runHook($this->postRun);
    }

    /**
     * @param mixed $postRun
     */
    public function setPostRun($postRun): self
    {
        $this->postRun = $postRun;

        return $this;
    }

    public function isPackageBump(): bool
    {
        return $this->packageBump;
    }

    public function setPackageBump(bool $packageBump): Configuration
    {
        $this->packageBump = $packageBump;

        return $this;
    }

    public function setPackageBumps(array $packageBumps): Configuration
    {
        $this->packageBumps = $packageBumps;

        return $this;
    }

    public function getPackageBumps(): array
    {
        return $this->packageBumps;
    }

    public function isPackageLockCommit(): bool
    {
        return $this->packageLockCommit;
    }

    public function setPackageLockCommit(bool $packageLockCommit): Configuration
    {
        $this->packageLockCommit = $packageLockCommit;

        return $this;
    }

    public function isHiddenVersionSeparator(): bool
    {
        return $this->hiddenVersionSeparator;
    }

    public function setHiddenVersionSeparator($hiddenVersionSeparator): Configuration
    {
        $this->hiddenVersionSeparator = $hiddenVersionSeparator;

        return $this;
    }

    /**
     * Validate settings.
     *
     * @param mixed $settings
     */
    public static function validate($settings): bool
    {
        return is_array($settings);
    }

    /**
     * Check if is regex.
     *
     * @return bool
     */
    protected function isRegex(string $pattern)
    {
        return @preg_match($pattern, '') !== false;
    }
}
