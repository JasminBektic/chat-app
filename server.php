<?php

use App\Core\Server;

require_once __DIR__ . '/config/config.php';

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('server\\', __DIR__);

Server::run();