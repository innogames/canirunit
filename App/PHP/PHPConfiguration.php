<?php

namespace App\PHP;

use App\CheckableInterface;
use App\MessageBag;

class PHPConfiguration implements CheckableInterface
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var mixed
     */
    private $value;

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
     * @return MessageBag
     */
    public function check()
    {
        $messages = new MessageBag("PHP Configuration ({$this->name})");

        $configurationValue = ini_get($this->name);
        if ($configurationValue === false) {
            $messages->addMessage("PHP configuration for {$this->name} doesn't exists");
            return $messages;
        }

        if ($this->value === null) {
            if ($configurationValue !== '') {
                $messages->addMessage("PHP configuration for {$this->name} is set to {$configurationValue}", true);
            } else {
                $messages->addMessage("PHP configuration for {$this->name} is not set to any value");
            }

            return $messages;
        }


        if ($configurationValue === $this->value) {
            $messages->addMessage(
                "PHP configuration for {$this->name} is equal to the desired value {$this->value}",
                true
            );
        } else {
            $messages->addMessage(
                "PHP configuration for {$this->name} is not equal to the desired value {$this->value}"
            );
        }

        return $messages;
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
