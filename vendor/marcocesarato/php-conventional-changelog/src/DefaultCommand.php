<?php

namespace ConventionalChangelog;

use ConventionalChangelog\Git\Repository;
use ConventionalChangelog\Helper\SemanticVersion;
use ConventionalChangelog\Helper\ShellCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DefaultCommand extends Command
{
    /**
     * Changelog.
     *
     * @var Changelog
     */
    public $changelog;

    /**
     * @var Configuration
     */
    public $config;

    /**
     * @var array
     */
    public $settings = [];
    /**
     * Command name.
     *
     * @var string
     */
    protected static $defaultName = 'changelog';
    /**
     * Output with style.
     *
     * @var SymfonyStyle
     */
    protected $output;

    /**
     * Constructor.
     */
    public function __construct(array $settings = [])
    {
        parent::__construct(self::$defaultName);
        $this->settings = $settings;
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription("Generate changelogs and release notes from a project's commit messages" .
                'and metadata and automate versioning with semver.org and conventionalcommits.org')
            ->setDefinition([
                new InputArgument('path', InputArgument::OPTIONAL, 'Specify the path directory where generate changelog'),
                new InputOption('config', null, InputOption::VALUE_REQUIRED, 'Specify the configuration file path'),
                new InputOption('commit', 'c', InputOption::VALUE_NONE, 'Commit the new release once changelog is generated'),
                new InputOption('amend', 'a', InputOption::VALUE_NONE, 'Amend commit the new release once changelog is generated'),
                new InputOption('commit-all', null, InputOption::VALUE_NONE, 'Commit all changes the new release once changelog is generated'),
                new InputOption('first-release', null, InputOption::VALUE_NONE, "Run at first release (if --ver isn't specified version code it will be 1.0.0)"),
                new InputOption('from-date', null, InputOption::VALUE_REQUIRED, 'Get commits from specified date [YYYY-MM-DD]'),
                new InputOption('to-date', null, InputOption::VALUE_REQUIRED, 'Get commits last tag date (or specified on --from-date) to specified date [YYYY-MM-DD]'),
                new InputOption('from-tag', null, InputOption::VALUE_REQUIRED, 'Get commits from specified tag'),
                new InputOption('to-tag', null, InputOption::VALUE_REQUIRED, 'Get commits last tag (or specified on --from-tag) to specified tag'),
                new InputOption('major', null, InputOption::VALUE_NONE, 'Major release (important changes)'),
                new InputOption('minor', null, InputOption::VALUE_NONE, 'Minor release (add functionality)'),
                new InputOption('patch', null, InputOption::VALUE_NONE, 'Patch release (bug fixes) [default]'),
                new InputOption('rc', null, InputOption::VALUE_NONE, 'Release candidate'),
                new InputOption('beta', null, InputOption::VALUE_NONE, 'Beta release'),
                new InputOption('alpha', null, InputOption::VALUE_NONE, 'Alpha release'),
                new InputOption('ver', null, InputOption::VALUE_REQUIRED, 'Specify the next release version code (semver)'),
                new InputOption('history', null, InputOption::VALUE_NONE, 'Generate the entire history of changes of all releases'),
                new InputOption('no-verify', null, InputOption::VALUE_NONE, 'Bypasses the pre-commit and commit-msg hooks'),
                new InputOption('no-tag', null, InputOption::VALUE_NONE, 'Disable release auto tagging'),
                new InputOption('no-change-without-commits', null, InputOption::VALUE_NONE, 'Do not apply change if no commits'),
                new InputOption('annotate-tag', null, InputOption::VALUE_OPTIONAL, 'Make an unsigned, annotated tag object once changelog is generated', false),
                new InputOption('merged', null, InputOption::VALUE_NONE, 'Only include commits whose tips are reachable from HEAD'),
            ]);
    }

    /**
     * Execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * Initialize.
         */
        $this->output = new SymfonyStyle($input, $output);
        if ($this->getApplication() !== null) {
            $appName = $this->getApplication()->getName();
            $appVersion = $this->getApplication()->getVersion();
            $this->output->title($appName . ' ' . $appVersion);
        }

        /**
         * Retrieve configuration settings.
         */
        $config = $input->getOption('config');
        if (!empty($config) && is_file($config)) {
            $this->settings = require $config;
        }
        if (!Configuration::validate($this->settings)) {
            $this->output->error('Not a valid configuration! Using default settings.');
            $this->settings = [];
        }
        $this->config = new Configuration($this->settings);
        // Get root path
        $root = $input->getArgument('path');
        if (empty($root) || !is_dir($root)) {
            $root = $this->config->getRoot();
        }
        // Set working directory
        chdir($root);

        /**
         * Check environment.
         */
        $this->output->writeln('Checking environment requirements');
        // Check shell exec function
        if (!ShellCommand::isEnabled()) {
            $this->output->error(
                'It looks like shell_exec function is disabled on disable_functions config of your php.ini settings file. ' .
                'Please check you configuration and enabled shell_exec to proceed.'
            );

            return 1; // Command::FAILURE;
        }
        $this->validRequirement('Commands executions enabled');
        // Check git command
        if (!ShellCommand::exists('git')) {
            $this->output->error(
                'It looks like Git is not installed on your system. ' .
                'Please check how to install it from https://git-scm.com before run this command.'
            );

            return 1; // Command::FAILURE;
        }
        $this->validRequirement('Git detected correctly');
        // Check git version
        $gitVersion = ShellCommand::exec('git --version');
        $gitSemver = new SemanticVersion($gitVersion);
        $gitVersionCode = $gitSemver->getVersionCode();
        if (version_compare($gitVersionCode, '2.1.4', '<')) {
            $this->output->error(
                'It looks like your Git version is ' . $gitVersionCode . ' that isn\'t compatible with this tool (min required is 2.1.4). ' .
                'Please check how to update it from https://git-scm.com before run this command.'
            );

            return 1; // Command::FAILURE;
        }
        $this->validRequirement('Git version detected: ' . $gitVersionCode);
        // Check working directory
        if (!Repository::isInsideWorkTree()) {
            $this->output->error('The directory "' . $root . '" isn\'t a valid git repository or isn\'t been detected correctly.');

            return 1; // Command::FAILURE;
        }
        $this->validRequirement('Valid git worktree directory: ' . $root);
        // Check git repository
        if (Repository::hasRemoteUrl()) {
            $url = Repository::getRemoteUrl();
            if (empty(Repository::parseRemoteUrl())) {
                $this->output->warning(
                    'The remote url of your git repository ("' . $url . '") not been parsed correctly. ' .
                    'Please open and issue including your repository url format to make it compatible with this tool here ' .
                    'https://github.com/marcocesarato/php-conventional-changelog/issues'
                );
            } else {
                $this->validRequirement('Valid git remote repository url: ' . $url);
            }
        } else {
            $this->output->warning(
                'Remote repository url not detected. ' .
                'It looks like your project is running only locally and not on a remote repository. ' .
                'Care, all urls on the changelog will be empty.'
            );
        }
        $this->output->newLine();

        /**
         * Generate changelog.
         */
        $this->output->writeln('Generating changelog');
        $this->changelog = new Changelog($this->config);

        return $this->changelog->generate($root, $input, $this->output);
    }

    /**
     * Print with valid requirement format.
     */
    protected function validRequirement(string $messages)
    {
        $this->output->writeln(' ✓ ' . $messages);
    }
}
