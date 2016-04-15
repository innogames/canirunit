<?php

namespace App;

use Composer\Semver\Semver;

class Version
{
    const NO_VERSION = 'Not Available';
    const ANY_VERSION = '*';
    const PATTERN_VERSION = '/\d{1,3}\.\d{1,3}.\d{1,3}/';

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function __construct($version = self::NO_VERSION)
    {
        $this->version = $version;
    }

    /**
     * @param string $command
     * @param string $pattern
     * @return $this|string
     */
    public function setVersionFromCommand($command, $pattern = self::PATTERN_VERSION)
    {
        $matches = [];
        preg_match($pattern, trim(`$command`), $matches);

        $this->version = self::NO_VERSION;
        if (count($matches) !== 0) {
            $this->version = $matches[0];
        }

        return $this;
    }

    /**
     * @param Version $compareToVersion
     * @return bool
     */
    public function compareVersion(Version $compareToVersion)
    {
        return Semver::satisfies($this->getVersion(), $compareToVersion->getVersion());
    }

    /**
     * @return bool
     */
    public function hasVersion()
    {
        return $this->getVersion() !== self::NO_VERSION;
    }

    /**
     * @return bool
     */
    public function isAnyVersion()
    {
        return $this->getVersion() === self::ANY_VERSION;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getVersion();
    }
}
