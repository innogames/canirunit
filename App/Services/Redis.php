<?php

namespace App\Services;

use App\CheckableInterface;
use App\MessageBag;
use App\Version;

class Redis implements CheckableInterface
{
    const COMMAND = 'redis-cli --version';
    const COMMAND_PING = "redis-cli -h '%s' -p %d -a '%s' PING 2>&1";
    const DEFAULT_PORT = 6379;

    /**
     * @var Version
     */
    private $requiredVersion;

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var int
     */
    private $port = self::DEFAULT_PORT;

    /**
     * @var MessageBag
     */
    protected $messages;

    /**
     * @param string $requiredVersion
     * @param string $host
     * @param string $password
     * @param int $port
     * @param bool $required
     */
    public function __construct($requiredVersion, $host, $password = '', $port = self::DEFAULT_PORT, $required = true)
    {
        $this->requiredVersion = new Version($requiredVersion);
        $this->host = $host;
        $this->password = $password;
        $this->port = $port;
        $this->messages = new MessageBag('Redis', $required);
    }

    /**
     * @return MessageBag
     */
    public function check()
    {
        $this->checkVersion()
            ->checkConnection();

        return $this->messages;
    }

    /**
     * @return Redis
     */
    private function checkVersion()
    {
        $version = new Version();
        $version->setVersionFromCommand(self::COMMAND);

        if (!$version->hasVersion()) {
            $this->messages->addMessage('Redis is NOT installed on your machine');
            return $this;
        }

        $this->messages->addMessage('Redis is installed on your machine', true);
        $this->messages->addMessage(
            "Redis required min version is {$this->requiredVersion} and current installed version is {$version}",
            $version->compareVersion($this->requiredVersion)
        );

        return $this;
    }

    /**
     * @return Redis
     */
    private function checkConnection()
    {
        $command = sprintf(self::COMMAND_PING, $this->host, $this->port, $this->password);
        $output = trim(`$command`);

        if ($output === 'PONG') {
            $this->messages->addMessage('Connection established to Redis', true);
        } else {
            $this->messages->addMessage("Error connecting to Redis: {$output}");
        }

        return $this;
    }
}
