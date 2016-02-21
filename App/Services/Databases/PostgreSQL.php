<?php

namespace App\Services\Databases;

use App\Checkable;

class PostgreSQL extends AbstractDatabase implements Checkable
{
    const PATTERN_VERSION = '/\d{1,3}\.\d{1,3}.\d{1,3}/';
    const PATTERN_CONNECTION = '/^.*:%d - accepting connections$/';

    /**
     * @param string $version
     * @param string $host
     * @param string $user
     * @param string $password
     * @param array $databases
     * @param int $port
     */
    public function __construct($version, $host, $user, $password, array $databases, $port = 5432)
    {
        $this->port = $port;
        parent::__construct($version, $host, $user, $password, $databases);
    }

    /**
     * @return string
     */
    protected function getDriver()
    {
        return self::DRIVER_POSTGRESQL;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function checkVersion()
    {
        $matches = [];
        $postgreVersion = trim(`psql --version`);
        preg_match(self::PATTERN_VERSION, $postgreVersion, $matches);

        if (empty($matches)) {
            throw new \Exception("PostgreSQL is not installed on your machine");
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function checkConnection()
    {
        $matches = [];
        preg_match(sprintf(self::PATTERN_CONNECTION, $this->port), trim(`pg_isready`), $matches);

        if (empty($matches)) {
            throw new \Exception("PostgreSQL is not accepting connections on your machine, start the server first");
        }

        return true;
    }
}
