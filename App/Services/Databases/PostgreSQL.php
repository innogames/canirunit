<?php

namespace App\Services\Databases;

use App\Version;

class PostgreSQL extends AbstractDatabase
{
    const PATTERN_VERSION = '/\d{1,3}\.\d{1,3}.\d{1,3}/';
    const PATTERN_CONNECTION = '/^.*:%d - accepting connections$/';
    const DEFAULT_PORT = 5432;

    /**
     * @param string $version
     * @param string $host
     * @param string $user
     * @param string $password
     * @param array $databases
     * @param int $port
     */
    public function __construct($version, $host, $user, $password, array $databases, $port = self::DEFAULT_PORT)
    {
        parent::__construct($version, $host, $user, $password, $databases, $port);
    }

    /**
     * @return string
     */
    protected function getDriver()
    {
        return self::DRIVER_POSTGRESQL;
    }

    /**
     * @return Version
     */
    protected function getVersion()
    {
        $matches = [];
        preg_match(self::PATTERN_VERSION, trim(`psql --version`), $matches);

        if (count($matches) === 0) {
            return new Version(Version::NO_VERSION);
        }

        return new Version($matches[0]);
    }

    /**
     * @return $this
     */
    protected function checkConnection()
    {
        $matches = [];
        preg_match(sprintf(self::PATTERN_CONNECTION, $this->port), trim(`pg_isready`), $matches);

        if (count($matches) === 0) {
            $this->messages->addMessage(
                "{$this->getName()} is not accepting connections on your machine, start the server first"
            );

            return $this;
        }


        $this->messages->addMessage("{$this->getName()} is started and running", true);

        return $this;
    }
}
