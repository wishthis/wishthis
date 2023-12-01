<?php

namespace ConventionalChangelog\Helper;

class SemanticVersion
{
    /**
     * Pattern to detect semver.
     *
     * @var string
     */
    public const PATTERN = '([0-9]+)\.([0-9]+)\.([0-9]+)(?:-([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?(?:\+[0-9A-Za-z-]+)?';

    /**
     * Pattern no extra.
     *
     * @var string
     */
    public const PATTERN_NO_EXTRA = '([0-9]+)\.([0-9]+)\.([0-9]+)';

    /**
     * @var string
     */
    public const MAJOR = 'major';
    /**
     * @var string
     */
    public const MINOR = 'minor';
    /**
     * @var string
     */
    public const PATCH = 'patch';
    /**
     * @var string
     */
    public const RC = 'rc';
    /**
     * @var string
     */
    public const BETA = 'beta';
    /**
     * @var string
     */
    public const ALPHA = 'alpha';

    /**
     * @var string
     */
    protected $version;

    /**
     * Constructor.
     */
    public function __construct($version, $prefix = 'v')
    {
        $version = trim($version);
        $version = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $version);

        $this->setVersion($version);
    }

    /**
     * Bump version.
     */
    public function bump(string $release, string $extraRelease = ''): string
    {
        $version = $this->getVersion();

        $newVersion = [0, 0, 0];

        if (!self::validate($version)) {
            return $version;
        }

        // Generate new version code
        $split = explode('-', $version, 2);
        $extra = empty($split[1]) ? '' : $split[1];

        $skipBumpRelease = false;
        $extraReleases = [self::RC, self::BETA, self::ALPHA];

        if (in_array($extraRelease, $extraReleases)) {
            $partsExtra = explode('.', $extra, 2);
            $extraVersion = 0;
            if (count($partsExtra) > 1) {
                $extraVersion = $partsExtra[1];
            }
            if (empty($extraVersion) || !is_numeric($extraVersion)) {
                $extraVersion = 0;
            } else {
                $skipBumpRelease = true;
            }
            $extraVersion++;
            $extra = "{$extraRelease}.{$extraVersion}";
        }

        $parts = explode('.', $split[0]);
        foreach ($parts as $key => $value) {
            $newVersion[$key] = (int)$value;
        }

        if (!$skipBumpRelease) {
            if ($release === self::MAJOR) {
                $newVersion[0]++;
                $newVersion[1] = 0;
                $newVersion[2] = 0;
            } elseif ($release === self::MINOR) {
                $newVersion[1]++;
                $newVersion[2] = 0;
            } elseif ($release === self::PATCH) {
                $newVersion[2]++;
            }
        }

        // Recompose semver
        $version = implode('.', $newVersion) . (empty($extra) ? '' : '-' . $extra);
        $this->setVersion($version);

        return $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getVersionCode(): string
    {
        if (preg_match('/' . self::PATTERN_NO_EXTRA . '/', $this->version, $match)) {
            return $match[0];
        }

        return '0.0.0';
    }

    public function setVersion(string $version): SemanticVersion
    {
        $this->version = $version;

        return $this;
    }

    public static function validate($version, $prefix = 'v'): bool
    {
        $version = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $version);

        return preg_match('/^' . self::PATTERN . '$/', $version);
    }

    public static function compareBase($v1, $v2)
    {
        $v1 = preg_replace('/^.*?(' . self::PATTERN_NO_EXTRA . ').*?$/', '$1', $v1);
        $v2 = preg_replace('/^.*?(' . self::PATTERN_NO_EXTRA . ').*?$/', '$1', $v2);

        return version_compare($v1, $v2);
    }
}
