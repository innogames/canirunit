<?php

use App\Module;
use App\PHPConfiguration;
use App\Services\Databases\PostgreSQL;

return [
    "php" => [
        new Module("php", "5.5.9", true),
        new Module("pdo_pgsql", Module::ANY_VERSION, true),
        new Module("mcrypt", Module::ANY_VERSION, true),
        new Module("mongo", Module::ANY_VERSION, true),
        new Module("xdebug", "2.*", false),
        new Module("tideways", "3.*", false),
    ],
    "configuration" => [
        new PHPConfiguration("date.timezone"),
        new PHPConfiguration("short_open_tag", "1"),
    ],
    "services" => [
        new PostgreSQL("9.3.*", "localhost", "foe", "toor", ["foe_master", "foe_game", "foe_sandbox"])
    ]
];