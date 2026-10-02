<?php

if (PHP_VERSION_ID < 80300) {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $cleanPath = preg_replace('#^/dispensasi-sekolah/public#i', '', $uri);
    if (empty($cleanPath) || $cleanPath === '') $cleanPath = '/';
    header("Location: http://localhost:8080" . $cleanPath);
    exit;
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Suppress all PHP errors and warnings immediately
error_reporting(0);
ini_set('display_errors', '0');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
