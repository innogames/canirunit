<?php

namespace App\PHP;

use App\Checkable;
use App\MessageBag;
use App\Version;

class PHPModule implements Checkable
{
    const MODULE_PHP = 'php';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var Version
     */
    private $requiredVersion;

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
        $this->requiredVersion = new Version($version);
        $this->required = $required;
    }

    /**
     * @return MessageBag
     */
    public function check()
    {
        $messages = new MessageBag("PHP Extension ({$this->getName()})", $this->isRequired());

        if ($this->requiredVersion->isAnyVersion()) {
            if (extension_loaded($this->getName())) {
                $messages->addMessage("PHP Extension with name {$this->getName()} is loaded", true);
            } else {
                $messages->addMessage("PHP Extension with name {$this->getName()} is not loaded");
            }

            return $messages;
        }

        $messages->addMessage("PHP Extension with name {$this->getName()} is loaded", true);

        $moduleVersion = $this->getModuleVersion();
        $messages->addMessage(
            "Required min version is {$this->getRequiredVersion()} and current installed version is {$moduleVersion}",
            $moduleVersion->compareVersion($this->requiredVersion)
        );

        return $messages;
    }

    /**
     * @return Version
     */
    private function getModuleVersion()
    {
        $moduleVersion = phpversion();
        if ($this->name !== self::MODULE_PHP) {
            $moduleVersion  = phpversion($this->getName());
        }

        if ($moduleVersion === false) {
            return new Version(Version::NO_VERSION);
        }

        return new Version($moduleVersion);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Version
     */
    public function getRequiredVersion()
    {
        return $this->requiredVersion;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }
}