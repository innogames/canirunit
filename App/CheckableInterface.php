<?php

namespace App;

interface CheckableInterface
{
    /**
     * @return MessageBag
     */
    public function check();
}
