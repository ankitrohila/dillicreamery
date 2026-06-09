<?php

// Vercel PHP runtime entry point for Laravel
// This file routes all requests through Laravel's public/index.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly if they exist
$publicPath = __DIR__ . '/../public';
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

// Boot Laravel application
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../public/index.php';
