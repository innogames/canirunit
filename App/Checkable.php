<?php

namespace App;

interface Checkable
{
    /**
     * @return MessageBag
     */
    public function check();
}