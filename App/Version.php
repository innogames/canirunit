<?php

namespace App;

use Composer\Semver\Semver;

class Version
{
    const NO_VERSION = 'Not Available';
    const ANY_VERSION = '*';

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
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
