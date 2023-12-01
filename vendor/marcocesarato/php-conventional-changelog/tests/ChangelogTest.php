<?php

namespace Tests;

use ConventionalChangelog\Changelog;
use ConventionalChangelog\Configuration;
use PHPUnit\Framework\TestCase;

class ChangelogTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private Configuration $configuration;
    private Changelog $changelog;

    protected function setUp(): void
    {
        $this->configuration = $this->createMock(Configuration::class);
        $this->changelog = new Changelog($this->configuration);
    }

    protected function setUpMocks($callback = null): void
    {
        $exec = $this->getFunctionMock(__NAMESPACE__, 'shell_exec');
        $exec->expects($this->any())->willReturnCallback(
            function ($command) use ($callback) {
                $output = null;
                if (!empty($callback)) {
                    $output = $callback($command);
                }
                if ($output !== null) {
                    return $output;
                }

                // getCommitDate
                $commitDate = 'git log -1 --format=%aI';
                if (substr($commitDate, 0, strlen($command)) === $command) {
                    return '2000-01-01T00:00:00+00:00';
                }

                switch ($command) {
                    case 'git config --get remote.origin.url':
                        // getRemoteUrl
                        return 'https://fake.remote.com/user/repo.git';
                    case 'git rev-list --max-parents=0 HEAD':
                        // getFirstCommit
                        return '021a49f43ef65ac7a894450374f1772eef1fd8b0';
                    case 'git log -1 --pretty=format:%H':
                        // getLastCommit
                        return '84d151f93f36474055c9ff406710a47aa3d6e168';
                    case 'git branch --show-current':
                        // getCurrentBranch
                        return 'main';
                    default:
                        break;
                }
            }
        );
    }

    /** @test */
    public function testRemoveHeader(): void
    {
        $class = new \ReflectionClass($this->changelog);
        $method = $class->getMethod('removeHeader');
        $method->setAccessible(true);

        $content = <<<EOF
<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## 0.0.1 (1970-01-01)

### Features

* Here we go !
EOF;

        $expectedContent = <<<EOF
## 0.0.1 (1970-01-01)

### Features

* Here we go !
EOF;

        $actualContent = $method->invokeArgs(
            $this->changelog,
            [$content]
        );

        $this->assertEquals($expectedContent, $actualContent);
    }

    /** @test */
    public function testExecMock()
    {
        $this->setUpMocks(function ($command) {
            if ($command == 'bar') {
                return 'foo';
            }
        });

        $output = shell_exec('git config --get remote.origin.url');
        $this->assertEquals('https://fake.remote.com/user/repo.git', $output);

        $output = shell_exec('bar');
        $this->assertEquals('foo', $output);
    }
}
