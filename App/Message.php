<?php

namespace App;

use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var bool
     */
    private $status = false;

    /**
     * @param string $message
     * @param bool $status
     */
    public function __construct($message, $status = false)
    {
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
        ];
    }
}
