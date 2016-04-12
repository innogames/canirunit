<?php

namespace App;

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
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->result = $this->validate();
    }

    /**
     * @return array
     */
    private function validate()
    {
        $result = [];
        foreach ($this->config as $module) {
            /** @var CheckableInterface $module */
            $result[] = $module->check();
        }

        return $result;
    }


    /**
     * Get the app validation as array
     *
     * @return array
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
