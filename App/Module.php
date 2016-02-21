<?php

namespace App;

class Module implements Checkable
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
     * @return boolean
     */
    public function check()
    {
        if ($this->version === self::ANY_VERSION) {
            return extension_loaded($this->name);
        }

        return version_compare(
            $this->getModuleVersion(),
            $this->version,
            self::BIGGER_OR_EQUAL_SIGN
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getModuleVersion()
    {
        $moduleVersion = phpversion();
        if ($this->name !== "php") {
            $moduleVersion  = phpversion($this->name);
        }

        if ($moduleVersion === false) {
            throw new \Exception("Module {$this->name} doesn't exists");
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