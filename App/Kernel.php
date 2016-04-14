<?php

namespace App;

use App\Exception\ConfigNotFoundException;

class Kernel
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $result = [];

    /**
     * @throws ConfigNotFoundException
     */
    public function __construct()
    {
        $configFile = __DIR__ . '/../config.php';
        if (!file_exists($configFile)) {
            throw new ConfigNotFoundException("Config file {$configFile} not found!");
        }

        $this->config = require_once $configFile;
        $this->result = $this->validate();
    }

    /**
     * @return array
     */
    private function validate()
    {
        $result = [];

        /** @var CheckableInterface $module */
        foreach ($this->config as $module) {
            $result[] = $module->check();
        }

        return $result;
    }


    /**
     * Get the app validation as array
     *
     * @return MessageBag[]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get the app validation as json
     *
     * @return string
     */
    public function getResultJson()
    {
        return json_encode($this->result);
    }
}
