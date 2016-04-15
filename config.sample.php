<?php

use App\PHP\PHPModule;
use App\PHP\PHPConfiguration;
use App\Services\Databases\PostgreSQL;
use App\Version;

return [
    // PHP Modules
    new PHPModule('php', '^5.6', true),
    new PHPModule('pdo_pgsql', Version::ANY_VERSION, true),
    new PHPModule('mcrypt', Version::ANY_VERSION, true),
    new PHPModule('mongo', Version::ANY_VERSION, true),
    new PHPModule('xdebug', '~2.4', false),
    new PHPModule('tideways', '3.*', false),

    // PHP Configuration
    new PHPConfiguration('date.timezone'),
    new PHPConfiguration('short_open_tag', '1'),

    // Services
    new PostgreSQL('9.3.*', 'hostname', 'username', 'password', ['database1', 'database2', 'database3']),
];
