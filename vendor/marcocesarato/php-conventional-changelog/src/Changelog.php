<?php

namespace ConventionalChangelog;

use ConventionalChangelog\Git\ConventionalCommit;
use ConventionalChangelog\Git\Repository;
use ConventionalChangelog\Helper\Formatter;
use ConventionalChangelog\Helper\SemanticVersion;
use ConventionalChangelog\PackageBump\ComposerJson;
use ConventionalChangelog\PackageBump\PackageJson;
use ConventionalChangelog\Type\PackageBump;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Changelog
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * Remote url parse.
     *
     * @var array
     */
    protected $remote = [];

    /**
     * Has valid remote url.
     *
     * @var bool
     */
    protected $hasValidRemoteUrl = false;

    /**
     * Changelog constructor.
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->remote = Repository::parseRemoteUrl();
    }

    /**
     * Generate changelog.
     */
    public function generate(string $root, InputInterface $input, SymfonyStyle $output): int
    {
        $nextVersion = $input->getOption('ver');
        $autoCommit = $input->getOption('commit'); // Commit once changelog is generated
        $autoCommitAll = $input->getOption('commit-all'); // Commit all changes once changelog is generated
        $autoTag = !($input->getOption('no-tag') || $this->config->skipTag()); // Tag release once is committed
        $noChangeWithoutCommits = $input->getOption('no-change-without-commits');
        $annotateTag = $input->getOption('annotate-tag');
        $amend = $input->getOption('amend'); // Amend commit
        $hooks = !$input->getOption('no-verify'); // Verify git hooks
        $hooks = $hooks && $this->config->skipVerify() ? false : true;
        $fromDate = $input->getOption('from-date');
        $toDate = $input->getOption('to-date');
        $fromTag = $input->getOption('from-tag');
        $toTag = $input->getOption('to-tag');
        $history = $input->getOption('history');
        $dateFormat = $this->config->getDateFormat();
        $sortBy = $this->config->getSortBy();
        $sortOrientation = $this->config->getSortOrientation($sortBy);
        $merged = $input->getOption('merged');

        $lastVersion = null;
        $firstRelease = $input->getOption('first-release');
        $alphaRelease = $input->getOption('alpha');
        $betaRelease = $input->getOption('beta');
        $preRelease = $input->getOption('rc');
        $patchRelease = $input->getOption('patch');
        $minorRelease = $input->getOption('minor');
        $majorRelease = $input->getOption('major');

        $tagPrefix = $this->config->getTagPrefix();
        $tagSuffix = $this->config->getTagSuffix();

        $autoCommit = $autoCommit || $autoCommitAll;
        $autoBump = false;
        /**
         * @var PackageBump[]
         */
        $packageBumps = [
            ComposerJson::class,
            PackageJson::class,
        ];

        // Allow config to specify own packages.
        if ($this->config->getPackageBumps()) {
            $packageBumps = $this->config->getPackageBumps();
        }

        $this->hasValidRemoteUrl = Repository::hasRemoteUrl() && !empty(Repository::parseRemoteUrl());

        // Hook pre run
        $this->config->preRun();

        // If have amend option enable commit
        if ($amend) {
            $autoCommit = true;
        }

        // Initialize changelogs
        $file = $this->config->getPath();
        $dirname = dirname($file);
        if (!is_file($file) && !is_dir($dirname)) {
            $file = $root . DIRECTORY_SEPARATOR . $file;
        }
        $changelogCurrent = '';
        $changelogNew = '';

        $mainHeaderPrefix = "<!--- BEGIN HEADER -->\n# ";
        $mainHeaderSuffix = "\n<!--- END HEADER -->\n\n";
        $mainHeaderContent = $this->config->getHeaderTitle() . "\n\n" . $this->config->getHeaderDescription();
        $mainHeader = $mainHeaderPrefix . $mainHeaderContent . $mainHeaderSuffix;

        // Get changelogs content
        if (file_exists($file)) {
            $changelogCurrent = $this->removeHeader(
                file_get_contents($file)
            );
        }

        // Current Dates
        $today = new \DateTime();
        // First release
        $newVersion = '1.0.0';
        // First commit
        $firstCommit = Repository::getFirstCommit();

        if (!$firstRelease) {
            $lastVersion = Repository::getLastTag($tagPrefix, $merged);
            $bumpRelease = SemanticVersion::PATCH;

            if ($majorRelease) {
                $bumpRelease = SemanticVersion::MAJOR;
            } elseif ($minorRelease) {
                $bumpRelease = SemanticVersion::MINOR;
            } elseif ($patchRelease) {
                $bumpRelease = SemanticVersion::PATCH;
            } elseif ($preRelease) {
                $bumpRelease = SemanticVersion::RC;
                $lastVersion = Repository::getLastTag($tagPrefix, $merged, SemanticVersion::RC);
                $autoBump = !$this->config->skipBump();
            } elseif ($betaRelease) {
                $bumpRelease = SemanticVersion::BETA;
                $lastVersion = Repository::getLastTag($tagPrefix, $merged, SemanticVersion::BETA);
                $autoBump = !$this->config->skipBump();
            } elseif ($alphaRelease) {
                $bumpRelease = SemanticVersion::ALPHA;
                $lastVersion = Repository::getLastTag($tagPrefix, $merged, SemanticVersion::ALPHA);
                $autoBump = !$this->config->skipBump();
            } else {
                $autoBump = !$this->config->skipBump();
            }

            // Generate new version code
            $semver = new SemanticVersion($lastVersion, $tagPrefix);
            $newVersion = $semver->bump($bumpRelease);
        }
        if (!empty($nextVersion)) {
            $newVersion = $nextVersion;
            $autoBump = false;
        }
        $newVersion = preg_replace('/^' . preg_quote($tagPrefix, '/') . '/', '', $newVersion);

        $options = []; // Git retrieve options per version

        if ($history) {
            $changelogCurrent = ''; // Clean changelog file
            $tags = Repository::getTags($tagPrefix);

            $previousTag = null;
            foreach ($tags as $key => $toTag) {
                $fromTag = $firstCommit;
                if (!empty($previousTag) && $key !== 0) {
                    $fromTag = $previousTag;
                }
                $commitDate = Repository::getCommitDate($toTag);
                $options[$toTag] = [
                    'from' => $fromTag,
                    'to' => $toTag,
                    'date' => $commitDate->format($dateFormat),
                    'options' => "{$fromTag}...{$toTag}",
                    'autoBump' => false,
                ];
                $previousTag = $toTag;
            }
            if ($autoCommit) {
                $options[$newVersion] = [
                    'from' => $lastVersion,
                    'to' => $newVersion,
                    'date' => $today->format($dateFormat),
                    'options' => "{$lastVersion}...HEAD",
                    'autoBump' => false,
                ];
            }
            $options = array_reverse($options);
        } else {
            if ($firstRelease) {
                // Get all commits from the first one
                $additionalParams = "{$firstCommit}...HEAD";
                $lastVersion = $firstCommit;
                if (empty($fromTag)) {
                    $fromTag = $firstCommit;
                }
            } else {
                // Get latest commits from last version date
                $additionalParams = "{$lastVersion}...HEAD";
                if (empty($fromTag)) {
                    $fromTag = $lastVersion;
                }
            }

            // Clean ranges
            if ((!empty($fromDate) || !empty($toDate)) &&
                empty($fromTag) &&
                empty($toTag)) {
                $additionalParams = '';
            }

            // Tag range
            if (!empty($fromTag) ||
                !empty($toTag)) {
                if (empty($toTag)) {
                    $toTag = 'HEAD';
                }
                $additionalParams = "{$fromTag}...{$toTag}";
            }

            // Date range
            if (!empty($fromDate) ||
                !empty($toDate)) {
                if (!empty($fromDate)) {
                    $additionalParams .= ' --since="' . date('Y-m-d', strtotime($fromDate)) . '"';
                }
                if (!empty($toDate)) {
                    $time = strtotime($toDate);
                    $additionalParams .= ' --before="' . date('Y-m-d', $time) . '"';
                    $today->setTimestamp($time);
                }
            }

            $options[$newVersion] = [
                'from' => $lastVersion,
                'to' => $newVersion,
                'date' => $today->format($dateFormat),
                'options' => $additionalParams,
                'autoBump' => $autoBump,
            ];
        }

        $summary = [];
        foreach ($this->config->getTypes() as $type) {
            $summary[$type] = 0;
        }

        foreach ($options as $params) {
            $commitsRaw = Repository::getCommits($params['options']);
            usort($commitsRaw, function ($x, $y) use ($sortBy, $sortOrientation) {
                if (property_exists($x, $sortBy)) {
                    if ($sortOrientation === 'ASC') {
                        return $x->{$sortBy} <=> $y->{$sortBy};
                    }

                    return $y->{$sortBy} <=> $x->{$sortBy};
                }

                return 0;
            });

            // Get all commits information
            $commits = [];
            foreach ($commitsRaw as $commitRaw) {
                $commit = ConventionalCommit::fromCommit($commitRaw);

                // Not a conventional commit
                if (!$commit->isValid()) {
                    continue;
                }

                // Check ignored commit
                $ignore = false;
                foreach ($this->config->getIgnorePatterns() as $pattern) {
                    if (preg_match($pattern, $commit->getHeader())) {
                        $ignore = true;
                        break;
                    }
                }
                // Add commit
                if (!$ignore) {
                    $commits[] = $commit;
                }
            }

            // Changes groups sorting
            $changes = [];
            foreach ($this->config->getTypes() as $type) {
                $changes[$type] = [];
            }

            // Group all changes to lists by type
            $types = $this->config->getAllowedTypes();
            foreach ($commits as $commit) {
                if (in_array($commit->getType(), $types) || $commit->isBreakingChange()) {
                    $itemKey = $this->getItemKey($commit->getDescription());
                    $breakingChanges = $commit->getBreakingChanges();
                    $type = (string)$commit->getType();
                    $scope = $commit->getScope();
                    if ($this->config->isPrettyScope()) {
                        $scope = $commit->getScope()->toPrettyString();
                    }
                    $hash = $commit->getHash();
                    foreach ($breakingChanges as $description) {
                        $breakingType = Configuration::BREAKING_CHANGES_TYPE;
                        $key = $this->getItemKey($description);
                        if (empty($description) || $itemKey === $key) {
                            $commit->setBreakingChange(true);
                            continue;
                        }
                        // Clone commit as breaking with different description message
                        $breakingCommit = new ConventionalCommit();
                        $breakingCommit->setType($breakingType)
                            ->setDescription($description)
                            ->setScope($scope)
                            ->setHash($hash);
                        $changes[$breakingType][(string)$scope][$key][$hash] = $breakingCommit;
                        $summary[$breakingType]++;
                    }
                    $changes[$type][(string)$scope][$itemKey][$hash] = $commit;
                    $summary[$type]++;
                }
            }

            if ($params['autoBump']) {
                $semver = new SemanticVersion($params['from'], $tagPrefix);
                $bumpRelease = SemanticVersion::PATCH;

                if ($summary['breaking_changes'] > 0) {
                    $bumpRelease = SemanticVersion::MAJOR;

                    if (version_compare($semver->getVersion(), '1.0.0', '<')) {
                        $bumpRelease = SemanticVersion::MINOR;
                    }
                } elseif ($summary['feat'] > 0) {
                    $bumpRelease = SemanticVersion::MINOR;
                }

                $newVersion = $semver->bump($bumpRelease);

                if ($preRelease) {
                    $extraRelease = Repository::getLastReleaseCandidateTag($tagPrefix, $merged);
                    $bumpExtra = SemanticVersion::RC;
                } elseif ($alphaRelease) {
                    $extraRelease = Repository::getLastAlphaTag($tagPrefix, $merged);
                    $bumpExtra = SemanticVersion::ALPHA;
                } elseif ($betaRelease) {
                    $extraRelease = Repository::getLastBetaTag($tagPrefix, $merged);
                    $bumpExtra = SemanticVersion::BETA;
                }

                if (!empty($bumpExtra)) {
                    $semVer = new SemanticVersion($extraRelease, $tagPrefix);
                    $newVersion = $semVer->bump($bumpRelease, $bumpExtra);
                }

                $params['to'] = $newVersion;
            }

            // Initialize changelogs
            $params['to'] = $this->getVersionCode($params['to'], $tagPrefix, $tagSuffix);
            $compareUrl = $this->getCompareUrl($params['from'], "{$tagPrefix}{$params['to']}{$tagSuffix}");
            $markdownCompareLink = $this->getMarkdownLink($params['to'], $compareUrl);
            $changeLogVersionHeading = $this->getChangelogVersionHeading($markdownCompareLink, $params['date']);
            $changelogNew .= $changeLogVersionHeading . "\n";
            // Add all changes list to new changelog
            $changelogNew .= $this->getMarkdownChanges($changes);
        }
        $filesToCommit = [$file];

        if (isset($commits) && !count($commits) && $noChangeWithoutCommits) {
            $output->warning('No commits since last version.');

            return 0; // Command::SUCCESS;
        }

        if ($this->config->isPackageBump()) {
            foreach ($packageBumps as $packageBump) {
                try {
                    /**
                     * @var PackageBump
                     */
                    $bumper = new $packageBump($root);
                    if ($bumper->exists()) {
                        $bumper->setVersion($newVersion);
                        $bumper->save();
                        $filesToCommit[] = $bumper->getFilePath();
                        if ($this->config->isPackageLockCommit()) {
                            $filesToCommit = array_merge($filesToCommit, $bumper->getExistingLockFiles());
                        }
                    }
                } catch (\Exception $e) {
                    $output->error('An error occurred bumping package version: ' . $e->getMessage());
                }
            }
            $filesToCommit = array_unique($filesToCommit);
        }

        // Print summary
        if (!empty($summary)) {
            $output->section('Summary');
            $elements = [];
            foreach ($summary as $type => $count) {
                if ($count > 0) {
                    $elements[] = $count . ' ' . $this->config->getTypeDescription($type);
                }
            }
            $output->listing($elements);
        }

        // Save new changelog prepending the current one
        file_put_contents($file, $mainHeader . "{$changelogNew}{$changelogCurrent}");
        $output->success('Changelog generated!');
        $output->writeln(" > Changelog file: {$file}");

        // Create commit
        if ($autoCommit) {
            if ($autoCommitAll) {
                Repository::addAll();
            }
            $message = $this->getReleaseCommitMessage($newVersion);
            $result = Repository::commit($message, $filesToCommit, $amend, $hooks);
            if ($result !== false) {
                $output->success('Release committed!');
                // Create tag
                if ($autoTag) {
                    $tag = $tagPrefix . $newVersion . $tagSuffix;
                    $result = Repository::tag($tag, $annotateTag);
                    if ($result !== false) {
                        $output->success("Release tagged with success! New version: {$tag}");
                    } else {
                        $output->error('An error occurred tagging the release!');

                        return 1; // Command::FAILURE;
                    }
                }
            } else {
                $output->error('An error occurred committing the release!');

                return 1; // Command::FAILURE;
            }
        }

        // Hook post run
        $this->config->postRun();

        return 0; // Command::SUCCESS;
    }

    protected function removeHeader(string $content): string
    {
        return ltrim(preg_replace(
            '#<!--- BEGIN HEADER -->.*<!--- END HEADER -->(.*?)#iUs',
            '\1',
            $content
        ));
    }

    /**
     * Get version code.
     */
    protected function getVersionCode(string $tag, string $prefix, string $suffix): string
    {
        $version = preg_replace('#^v#i', '', $tag);

        // Remove tag prefix
        $rePrefix = '/^' . preg_quote($prefix, '/') . '/';
        $version = preg_replace($rePrefix, '', $version);

        // Remove tag suffix
        $reSuffix = '/' . preg_quote($suffix, '/') . '$/';
        $version = preg_replace($reSuffix, '', $version);

        return $version;
    }

    /**
     * Generate item key for merge commit with similar description.
     */
    protected function getItemKey(string $string): string
    {
        return mb_strtolower(preg_replace('/\s+/', '', $string));
    }

    /**
     * Generate markdown from changes.
     *
     * @param ConventionalCommit[][][][]  $changes
     */
    protected function getMarkdownChanges(array $changes): string
    {
        $changelog = '';
        // Add all changes list to new changelog
        foreach ($changes as $type => $list) {
            if (empty($list)) {
                continue;
            }
            $label = $this->config->getTypeLabel($type);
            $changelog .= "\n### {$label}\n\n";
            ksort($list);
            foreach ($list as $scope => $items) {
                if ($this->config->getSortBy() === 'subject') {
                    ksort($items, SORT_NATURAL);
                }
                if (is_string($scope) && !empty($scope)) {
                    // scope section
                    $changelog .= "\n##### {$scope}\n\n";
                }
                foreach ($items as $itemsList) {
                    $description = '';
                    $sha = '';
                    $references = '';
                    $mentions = '';
                    $shaGroup = [];
                    $refsGroup = [];
                    $mentionsGroup = [];
                    foreach ($itemsList as $item) {
                        $description = ucfirst($item->getDescription());
                        // Hashes
                        if (!$this->config->isHiddenHash() && !empty($item->getHash())) {
                            $commitUrl = $this->getCommitUrl($item->getHash());
                            $shaGroup[] = $this->getMarkdownLink($item->getShortHash(), $commitUrl);
                        }
                        // References
                        if (!$this->config->isHiddenReferences()) {
                            $refs = $item->getReferences();
                            foreach ($refs as $ref) {
                                $refId = $ref->getId();
                                $refUrl = $this->getIssueUrl($refId);
                                $refsGroup[] = $this->getMarkdownLink($ref, $refUrl);
                            }
                        }
                        // Mentions
                        $commitMentions = $item->getMentions();
                        foreach ($commitMentions as $mention) {
                            $user = $mention->getUser();
                            $userUrl = $this->getUserUrl($user);
                            $text = $this->getMarkdownLink("*{$mention}*", $userUrl);
                            if (strpos($description, (string)$mention) !== false) {
                                $description = str_replace($mention, $text, $description);
                            } elseif (!$this->config->isHiddenMentions()) {
                                $mentionsGroup[] = $text;
                            }
                        }
                    }

                    if (!$this->config->isHiddenHash() && !empty($shaGroup)) {
                        $sha = '(' . implode(', ', $shaGroup) . ')';
                    }
                    if (!$this->config->isHiddenReferences() && !empty($refsGroup)) {
                        $references = implode(', ', $refsGroup);
                    }
                    if (!$this->config->isHiddenMentions() && !empty($mentionsGroup)) {
                        $mentions = '*[*' . implode(', ', $mentionsGroup) . '*]*';
                    }
                    $changelog .= Formatter::clean("* {$description} {$references} {$sha} {$mentions}");
                    $changelog .= PHP_EOL;
                }
            }
        }
        // Add version separator
        if (!$this->config->isHiddenVersionSeparator()) {
            $changelog .= "\n\n---\n";
        }

        $changelog .= "\n";

        return $changelog;
    }

    /**
     * Get Markdown link.
     */
    protected function getMarkdownLink(string $text, string $url): string
    {
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url); // Remove double slashes

        return !$this->config->isDisableLinks() ? "[{$text}]({$url})" : $text;
    }

    protected function getChangelogVersionHeading($markdownCompareLink, $versionDate)
    {
        $versionFormat = $this->config->getChangelogVersionFormat();

        // Handle the replacements.
        $versionData = $this->getCompiledString($versionFormat, [
            'version' => $markdownCompareLink,
            'date' => $versionDate,
        ]);

        return $versionData;
    }

    /**
     * Compile string with values.
     */
    public function getCompiledString(string $format, array $values): string
    {
        $string = $format;
        $values = array_merge($this->remote, $values);
        foreach ($values as $key => $value) {
            $string = str_replace('{{' . $key . '}}', $value, $string);
        }

        return $string;
    }

    /**
     * Get commit url.
     */
    public function getCommitUrl(string $hash): string
    {
        if (!$this->hasValidRemoteUrl) {
            return '#';
        }

        $protocol = $this->config->getUrlProtocol();
        $format = $this->config->getCommitUrlFormat();
        $url = $this->getCompiledString($format, ['hash' => $hash]);

        return "{$protocol}://{$url}";
    }

    /**
     * Get commit compare url.
     */
    public function getCompareUrl(string $previousTag, string $currentTag): string
    {
        if (!$this->hasValidRemoteUrl) {
            return '#';
        }

        $protocol = $this->config->getUrlProtocol();
        $format = $this->config->getCompareUrlFormat();
        $url = $this->getCompiledString($format, ['previousTag' => $previousTag, 'currentTag' => $currentTag]);

        return "{$protocol}://{$url}";
    }

    /**
     * Get issue url.
     */
    public function getIssueUrl(string $id): string
    {
        if (!$this->hasValidRemoteUrl) {
            return '#';
        }

        $protocol = $this->config->getUrlProtocol();
        $format = $this->config->getIssueUrlFormat();
        $url = $this->getCompiledString($format, ['id' => $id]);

        return "{$protocol}://{$url}";
    }

    /**
     * Get user url.
     */
    public function getUserUrl(string $user): string
    {
        if (!$this->hasValidRemoteUrl) {
            return '#';
        }

        $protocol = $this->config->getUrlProtocol();
        $format = $this->config->getUserUrlFormat();
        $url = $this->getCompiledString($format, ['user' => $user]);

        return "{$protocol}://{$url}";
    }

    /**
     * Get release commit message.
     */
    public function getReleaseCommitMessage(string $tag): string
    {
        $format = $this->config->getReleaseCommitMessageFormat();

        return $this->getCompiledString($format, ['currentTag' => $tag]);
    }
}
