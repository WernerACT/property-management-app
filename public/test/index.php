<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));


// Register the Composer autoloader...
require '/usr/home/radsahbade/property-management-app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once '/usr/home/radsahbade/property-management-app/bootstrap/app.php')
    ->handleRequest(Request::capture());
