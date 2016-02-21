<?php

namespace App\Services\Databases;

use PDO;
use PDOException;
use App\Checkable;

abstract class AbstractDatabase implements Checkable
{
    const DRIVER_POSTGRESQL = "pgsql";
    const DRIVER_MYSQL = "mysql";

    /**
     * @var string
     */
    protected $version = "";

    /**
     * @var string
     */
    protected $host = "";

    /**
     * @var string
     */
    protected $user = "";

    /**
     * @var string
     */
    protected $password  = "";

    /**
     * @var int
     */
    protected $port = 0;

    /**
     * @var array
     */
    protected $databases = [];

    /**
     * @param string $version
     * @param string $host
     * @param string $user
     * @param string $password
     * @param array $databases
     */
    public function __construct($version, $host, $user, $password, array $databases)
    {
        $this->version = $version;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->databases = $databases;
    }

    /**
     * @return string
     */
    abstract protected function getDriver();

    /**
     * @return bool
     */
    abstract protected function checkVersion();

    /**
     * @return bool
     */
    abstract protected function checkConnection();

    /**
     * @return bool
     */
    public function check()
    {
        $this->checkVersion();
        $this->checkConnection();
        $this->checkDatabases();

        return true;
    }

    /**
     * @return bool
     */
    protected function checkDatabases()
    {
        foreach ($this->databases as $database) {
            $this->connect($database);
        }

        return true;
    }

    /**
     * @param string $database
     * @return bool
     */
    protected function connect($database)
    {
        try {
            new PDO($this->getDriver() . ":host={$this->host};dbname={$database}", $this->user, $this->password);
        } catch (PDOException $e) {
            echo "Error connecting to the database!: " . $e->getMessage() . "<br/>";
            return false;
        }

        return true;
    }
}