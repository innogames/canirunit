<?php

namespace App\PHP;

use App\Checkable;
use App\CheckResult;

class PHPModule implements Checkable
{
    const BIGGER_OR_EQUAL_SIGN = ">=";
    const ANY_VERSION = "*";

    /**
     * @var string
     */
    private $name = "";

    /**
     * @var string
     */
    private $version = "";

    /**
     * @var bool
     */
    private $required = false;

    /**
     * @param string $name
     * @param string $version
     * @param bool $required
     */
    public function __construct($name, $version, $required)
    {
        $this->name = $name;
        $this->version = $version;
        $this->required = $required;
    }

    /**
     * @return CheckResult
     */
    public function check()
    {
        $checkResult = new CheckResult("PHP Extension ({$this->getName()})", $this->isRequired());

        if ($this->version === self::ANY_VERSION) {
            if (extension_loaded($this->getName())) {
                $checkResult->setStatus(true);
                $checkResult->addMessage("PHP Extension with name {$this->getName()} is loaded");
            } else {
                $checkResult->addMessage("PHP Extension with name {$this->getName()} is not loaded");
            }

            return $checkResult;
        }

        if (version_compare($this->getModuleVersion(), $this->version, self::BIGGER_OR_EQUAL_SIGN)) {
            $checkResult->setStatus(true);
            $checkResult->addMessage("PHP Extension with name {$this->getName()} is loaded and matches the min version requirement");
        } else {
            $checkResult->addMessage("PHP Extension with name {$this->getName()} is loaded but doesn't match the required min version");
        }

        $checkResult->addMessage("Required min version is {$this->getVersion()} and current installed version is {$this->getModuleVersion()}");

        return $checkResult;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getModuleVersion()
    {
        $moduleVersion = phpversion();
        if ($this->name !== "php") {
            $moduleVersion  = phpversion($this->getName());
        }

        if ($moduleVersion === false) {
            throw new \Exception("Module {$this->getName()} doesn't exists");
        }

        return $moduleVersion;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }
}