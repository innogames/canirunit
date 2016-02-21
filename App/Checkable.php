<?php

namespace App;

interface Checkable
{
    /**
     * @return boolean
     */
    public function check();
}