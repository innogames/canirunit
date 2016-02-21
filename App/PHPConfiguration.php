<?php

namespace App;

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
     * @return bool
     * @throws \Exception
     */
    public function check()
    {
        $configurationValue = ini_get($this->name);
        if ($configurationValue === false) {
            throw new \Exception("PHP configuration {$this->name} doesn't exists");
        }

        if ($this->value === null) {
            return $configurationValue !== '';
        }

        return $configurationValue === $this->value;
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