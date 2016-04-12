<?php

namespace App\Services\Databases;

use PDO;
use PDOException;
use ReflectionClass;
use App\CheckableInterface;
use App\MessageBag;
use App\Version;

abstract class AbstractDatabase implements CheckableInterface
{
    const DRIVER_POSTGRESQL = 'pgsql';
    const DRIVER_MYSQL = 'mysql';

    /**
     * @var Version
     */
    protected $requiredVersion;

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var string
     */
    protected $user = '';

    /**
     * @var string
     */
    protected $password  = '';

    /**
     * @var int
     */
    protected $port = 0;

    /**
     * @var array
     */
    protected $databases = [];

    /**
     * @var MessageBag
     */
    protected $messages;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @param string $version
     * @param string $host
     * @param string $user
     * @param string $password
     * @param array $databases
     * @param int $port
     */
    public function __construct($version, $host, $user, $password, array $databases, $port)
    {
        $this->parseName();

        $this->requiredVersion = new Version($version);
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->databases = $databases;
        $this->port = $port;
        $this->messages = new MessageBag("Database {$this->getName()} ({$this->getDriver()})");
    }

    /**
     * @return string
     */
    abstract protected function getDriver();

    /**
     * @return Version
     */
    abstract protected function getVersion();

    /**
     * @return $this
     */
    abstract protected function checkConnection();

    /**
     * @return MessageBag
     */
    public function check()
    {
        $this->checkVersion()
            ->checkConnection()
            ->checkDatabases();

        return $this->messages;
    }

    /**
     * @return $this
     */
    protected function checkVersion()
    {
        $version = $this->getVersion();
        if (!$version->hasVersion()) {
            $this->messages->addMessage("{$this->getName()} database is not installed on your machine");
        } else {
            $this->messages->addMessage("{$this->getName()} database is installed on your machine", true);
        }

        $this->messages->addMessage(
            "{$this->getName()} required min version is {$this->requiredVersion} and current installed version is {$version}",
            $version->compareVersion($this->requiredVersion)
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function checkDatabases()
    {
        foreach ($this->databases as $database) {
            $this->connect($database);
        }

        return $this;
    }

    /**
     * @param string $database
     */
    protected function connect($database)
    {
        try {
            new PDO($this->getDriver() . ":host={$this->host};dbname={$database}", $this->user, $this->password);
            $this->messages->addMessage("Connection established to {$this->getName()} database {$database}!", true);
        } catch (PDOException $e) {
            $this->messages->addMessage(
                "Error connecting to the {$this->getName()} database! Code: {$e->getCode()}, Message: {$e->getMessage()}"
            );
        }
    }

    /**
     * @return $this
     */
    protected function parseName()
    {
        $reflection = new ReflectionClass(static::class);
        $this->name = $reflection->getShortName();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getName()
    {
        return $this->name;
    }
}
