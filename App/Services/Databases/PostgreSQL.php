<?php

namespace App\Services\Databases;

class PostgreSQL extends AbstractDatabase
{
    const COMMAND = 'psql --version';
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
     * @return PostgreSQL
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
