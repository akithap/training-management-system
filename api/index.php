<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// CRITICAL FOR VERCEL: Force Laravel to use the ephemeral Writable /tmp disk for EVERYTHING it tries to cache or write to internally.
$app->useStoragePath('/tmp');

// Handle the request!
$app->handleRequest(Request::capture());
