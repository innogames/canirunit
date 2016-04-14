<?php

namespace App;

use JsonSerializable;

class MessageBag implements JsonSerializable
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var bool
     */
    private $required = true;

    /**
     * @var Message[]
     */
    private $messages = [];

    /**
     * @param $title
     * @param bool $required
     */
    public function __construct($title, $required = true)
    {
        $this->title = $title;
        $this->required = $required;
    }

    /**
     * @param string $message
     * @param bool $status
     * @return $this
     */
    public function addMessage($message, $status = false)
    {
        $this->messages[] = new Message($message, $status);

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'title' => $this->getTitle(),
            'required' => $this->isRequired(),
            'messages' => $this->getMessages(),
        ];
    }
}
