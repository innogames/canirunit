<?php

namespace App\PHP;

use App\CheckableInterface;
use App\MessageBag;
use App\Version;

class PHPModule implements CheckableInterface
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
     * @var MessageBag
     */
    private $messages;

    /**
     * @param string $name
     * @param string $version
     * @param bool $required
     */
    public function __construct($name, $version, $required = true)
    {
        $this->name = $name;
        $this->requiredVersion = new Version($version);
        $this->messages = new MessageBag("PHP Extension ({$this->getName()})", $required);
    }

    /**
     * @return MessageBag
     */
    public function check()
    {
        if ($this->requiredVersion->isAnyVersion()) {
            if (extension_loaded($this->getName())) {
                $this->messages->addMessage("PHP Extension with name {$this->getName()} is loaded", true);
            } else {
                $this->messages->addMessage("PHP Extension with name {$this->getName()} is not loaded");
            }

            return $this->messages;
        }

        $this->messages->addMessage("PHP Extension with name {$this->getName()} is loaded", true);

        $moduleVersion = $this->getModuleVersion();
        $this->messages->addMessage(
            "Required min version is {$this->getRequiredVersion()} and current installed version is {$moduleVersion}",
            $moduleVersion->compareVersion($this->requiredVersion)
        );

        return $this->messages;
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
}
