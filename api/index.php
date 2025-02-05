<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__ . '/../vendor/autoload.php'; // Include composer autoload

// Set the path to the public folder so Laravel knows where to look for public assets
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Create an instance of the Kernel class to handle the request
$kernel = $app->make(Kernel::class);

// Capture the incoming request
$request = Request::capture();

// Handle the request and get the response
$response = $kernel->handle($request);

// Send the response to the browser
$response->send();

// Terminate the request (clean-up tasks like logging, etc.)
$kernel->terminate($request, $response);
