<?php

namespace App;

interface Checkable
{
    /**
     * @return CheckResult
     */
    public function check();
}