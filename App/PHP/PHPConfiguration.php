<?php

namespace App\PHP;

use App\Checkable;
use App\CheckResult;

class PHPConfiguration implements Checkable
{
    /**
     * @var string
     */
    private $name = "";

    /**
     * @var mixed
     */
    private $value = null;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return CheckResult
     */
    public function check()
    {
        $checkResult = new CheckResult("PHP Configuration ({$this->name})");

        $configurationValue = ini_get($this->name);
        if ($configurationValue === false) {
            $checkResult->addMessage("PHP configuration for {$this->name} doesn't exists");
            return $checkResult;
        }

        if ($this->value === null) {
            if ($configurationValue !== '') {
                $checkResult->setStatus(true);
                $checkResult->addMessage("PHP configuration for {$this->name} is set to {$configurationValue}");
            } else {
                $checkResult->addMessage("PHP configuration for {$this->name} is not set to any value");
            }

            return $checkResult;
        }


        if ($configurationValue === $this->value) {
            $checkResult->setStatus(true);
            $checkResult->addMessage("PHP configuration for {$this->name} is equal to the desired value {$this->value}");
        } else {
            $checkResult->addMessage("PHP configuration for {$this->name} is not equal to the desired value {$this->value}");
        }

        return $checkResult;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}